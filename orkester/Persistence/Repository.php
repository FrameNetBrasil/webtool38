<?php

namespace Orkester\Persistence;

use Illuminate\Support\Arr;
use Orkester\Persistence\Criteria\Criteria;
use Orkester\Persistence\Map\AssociationMap;
use Orkester\Persistence\Map\ClassMap;

class Repository
{
    protected static array $className;
//    public static abstract function map(ClassMap $classMap);

//    public static function __construct(PersistenceManager $pm = null)
//    {
//    }

//    public static function __construct(string $className = '')
//    {
//        $this->className = $className;
//    }

    protected static function getClassName(): string
    {
        $class = get_called_class();
        if (!isset(static::$className[$class])) {
            $className = str_replace("App\\Repositories\\", "", $class);
            debug("className", $className);
            static::$className[$class] = $className;
        }
        return static::$className[$class];
    }

    public static function getCriteria(string $databaseName = null): Criteria
    {
        $classMap = PersistenceManager::getClassMap(self::getClassName());
        $connection = PersistenceManager::getConnection($databaseName);
        $criteria = new Criteria($connection);
        return $criteria->setClassMap($classMap);
    }
//    public static function getCriteria(string $databaseName = null): Criteria
//    {
//        return PersistenceManager::getCriteria($databaseName, $this->className);
//    }

    public static function list(object|array|null $filter = null, array $select = [], array|string $order = ''): array
    {
        //$criteria = static::filter($filter);
        $criteria = static::getCriteria();
        if (!empty($select)) {
            $criteria->select($select);
        }
        $criteria->filter($filter);
        $criteria->order($order);
        return $criteria->get()->toArray();
    }

    public static function filter(array|null $filters, Criteria|null $criteria = null): Criteria
    {
        $criteria = $criteria ?? static::getCriteria();
        if (!empty($filters)) {
            $filters = is_string($filters[0]) ? [$filters] : $filters;
            foreach ($filters as [$field, $op, $value]) {
                $criteria->where($field, $op, $value);
            }
        }
        return $criteria;
    }

    public static function one($conditions, array $select = []): array|null
    {
        $criteria = static::getCriteria()->range(1, 1);
        if (!empty($select)) {
            $criteria->select($select);
        }
        $result = static::filter($conditions, $criteria)->get()->toArray();
        return empty($result) ? null : $result[0];
    }

    public static function find(int $id, array $columns = null): ?array
    {
        $columns ??= static::getClassMap()->getAttributesNames();
        return static::getCriteria()->find($id, $columns);
    }

//    public static function getClassMap(): ClassMap
//    {
//        return PersistenceManager::getClassMap($this->className);
//    }

    public static function getKeyAttributeName(): string
    {
        return static::getClassMap()->keyAttributeName;
    }

    public static function getAssociation(string $associationChain, int $id): array
    {
        return static::getCriteria()
            ->select($associationChain . '.*')
            ->where('id', '=', $id)
            ->get()
            ->toArray();
    }

    public static function deleteAssociation(string $associationChain, int $id)
    {
        return static::getCriteria()
            ->select($associationChain . '.*')
            ->where('id', '=', $id)
            ->delete();
    }

    public static function save(Model $obj): ?int
    {
        $data = get_object_vars($this);
        $key = $this->model::getKeyAttributeName();
        if (is_null($data[$key])) {
            unset($data[$key]);
        }
        $this->$key = $this->model::save($data);

        $fields = static::prepareWrite($data);
        $key = static::getKeyAttributeName();
        $criteria = self::getCriteria();
        $criteria->upsert([$fields], [$key], array_keys($fields));
        if (isset($data[$key])) {
            return $data[$key];
        } else {
            return $criteria->getConnection()->getPdo()->lastInsertId();
        }
    }

    protected function prepareWrite(array $data): array
    {
        $classMap = PersistenceManager::getClassMap($this->className);
        $validAttributes = array_keys($classMap->insertAttributeMaps);
        return Arr::only($data, $validAttributes);
    }

    public static function insert(array $data): int|string
    {
        $row = static::prepareWrite($data);
        $criteria = static::getCriteria();
        $criteria->insert($row);
        return $criteria->getConnection()->getPdo()->lastInsertId() ?? 0;
    }

    public static function update(array $data): bool
    {
        $row = static::prepareWrite($data);
        return static::getCriteria()
            ->where(static::getKeyAttributeName(), '=', $row[static::getKeyAttributeName()])
            ->update($row);
    }

    public static function upsert(array $data, array $uniqueBy, $updateColumns = null): ?int
    {
        $row = static::prepareWrite($data);
        $criteria = static::getCriteria();
        return $criteria->upsert($row, $uniqueBy, $updateColumns);
    }

    public static function delete($id)
    {
        return static::getCriteria()
            ->where(static::getKeyAttributeName(), '=', $id)
            ->delete();
    }

    public static function insertUsingCriteria(array $fields, Criteria $usingCriteria): ?int
    {
//        $classMap = PersistenceManager::getClassMap($this->className);
        $usingCriteria->applyBeforeQueryCallbacks();
        $criteria = static::getCriteria();
        $criteria->insertUsing($fields, $usingCriteria);
        $lastInsertId = $criteria->getConnection()->getPdo()->lastInsertId();
        return $lastInsertId;
    }

//    public static function getName(): string
//    {
//        $parts = explode('\\', $this->className);
//        $className = $parts[count($parts) - 1];
//        return substr($className, 0, strlen($className) - 5);
//    }

    public static function criteriaByFilter(object|null $params, array $select = []): Criteria
    {
        $criteria = static::getCriteria();
        if (!empty($select)) {
            $criteria->select($select);
        }
        if (!is_null($params)) {
            if (!empty($params->pagination->rows)) {
                $page = $params->pagination->page ?? 1;
                //mdump('rows = ' . $params->pagination->rows);
                //mdump('offset = ' . $offset);
                $criteria->range($page, $params->pagination->rows);
            }
            if (!empty($params->pagination->sort)) {
                $criteria->orderBy(
                    $params->pagination->sort . ' ' .
                    $params->pagination->order
                );
            }
        }
        return static::filter($params->filter, $criteria);
    }

    public static function exists(array $conditions): bool
    {
        return !is_null(static::one($conditions));
    }

    protected static function getManyToManyAssociation(string $associationName): AssociationMap
    {
        $classMap = PersistenceManager::getClassMap(get_called_class());
        $association = $classMap->getAssociationMap($associationName);
        if (empty($association)) {
            throw new \InvalidArgumentException("Unknown association: $associationName");
        }
        if (empty($association->associativeTable)) {
            throw new \InvalidArgumentException("append association requires associative table");
        }
        return $association;
    }

    public static function appendManyToMany(string $associationName, mixed $id, array $associatedIds): int
    {
        $association = static::getManyToManyAssociation($associationName);
        $columns = array_map(fn($aId) => [
            $association->toKey => $aId,
            $association->fromKey => $id
        ],
            $associatedIds
        );
        $classMap = PersistenceManager::getClassMap("{$association->fromClassName}_$association->associativeTable");
        return static::getCriteria()
            ->setClassMap($classMap)
            ->upsert($columns, [$association->toKey, $association->fromKey]);
    }

    public static function deleteManyToMany(string $associationName, mixed $id, ?array $associatedIds): void
    {
        $association = static::getManyToManyAssociation($associationName);
        $criteria = static::getCriteria();
        $criteria
            ->setClassMap(PersistenceManager::getClassMap("{$association->fromClassName}_$association->associativeTable"))
            ->where($association->fromKey, '=', $id);
        if (is_array($associatedIds)) {
            $criteria->where($association->toKey, 'IN', $associatedIds);
        }
        $criteria->delete();
    }

    public static function replaceManyToMany(string $associationName, mixed $id, array $associatedIds): void
    {
        PersistenceManager::beginTransaction();
        self::deleteManyToMany($associationName, $id, null);
        self::appendManyToMany($associationName, $id, $associatedIds);
        PersistenceManager::commit();
    }

    public static function getAssociationMap(string $name): ?AssociationMap
    {
        return static::getClassMap()->getAssociationMap($name);
    }

    /*
    public static function table(string $name)
    {
        static::getClassMap()->table($name);
    }

    public static function attribute(
        string $name,
        string $field = '',
        Type   $type = Type::STRING,
        Key    $key = Key::NONE,
        string $reference = '',
        string $alias = '',
        string $default = null,
        bool   $nullable = true,
        bool   $virtual = false)
    {
        static::getClassMap()->attribute($name, $field, $type, $key, $reference, $alias, $default, $nullable, $virtual);
    }

    public static function associationMany(string $name,
                                           string $model,
                                           string $keys = '',
                                           Join   $join = Join::INNER,
                                           string $associativeTable = '',
                                           string $order = '')
    {
        static::getClassMap()->associationMany($name, $model, $keys, $join, $associativeTable, $order);
    }

    public static function associationOne(
        string $name,
        string $model = '',
        string $key = '',
        string $base = '',
        array  $conditions = [],
        Join   $join = Join::INNER,
    ) {
        static::getClassMap()->associationOne($name, $model, $key, $base, $conditions, $join);
    }
    */
}
