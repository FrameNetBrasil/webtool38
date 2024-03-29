<?php

namespace Maestro\Persistence;

use Illuminate\Support\Facades\DB;
use Orkester\Manager;
use Orkester\Persistence\Enum\Association;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\PersistenceManager;
use ReflectionClass;

class Repository
{
    protected Model $model;
    protected Transaction $transaction;

    public function __construct(string $modelClass, int $id = null)
    {
        $this->model = new $modelClass();
        $this->initProperties();
        if (!is_null($id)) {
            $this->getById($id);
        }
    }

    public function initProperties()
    {
        $attributeMaps = $this->model->getClassMap()->getAttributeMaps();
        foreach ($attributeMaps as $map) {
            $attributeName = $map->name;
            $this->$attributeName = null;
        }
    }

    public function getCriteria(): Criteria
    {
        return $this->model::getCriteria();
    }

    public function beginTransaction()
    {
        $this->transaction = new Transaction();
        $this->transaction->beginTransaction();
    }

    public function commit()
    {
        $this->transaction->commit();
    }

    public function rollback()
    {
        $this->transaction->rollback();
    }

    public function setPersistent(bool $status = false)
    {
        $key = $this->model::getKeyAttributeName();
        if (!$status) {
            $this->$key = null;
        }
    }

    public function getId()
    {
        $key = $this->model::getKeyAttributeName();
        return $this->$key;
    }

    public function getById(int $id): void
    {
        $data = $this->model::find($id);
        if (!is_null($data)) {
            $this->setData($data);
        }
    }

    public function save(): ?int
    {
        $data = get_object_vars($this);
        $key = $this->model::getKeyAttributeName();
        if (is_null($data[$key])) {
            unset($data[$key]);
        }
        $this->$key = $this->model::save($data);
        return $this->$key;
    }

    public function setData($data)
    {
        foreach ($data as $attribute => $value) {
            $this->$attribute = $value;
        }
    }

    public function getData()
    {
        return $this;
    }

    public function saveData($data): ?int
    {
        $this->setData($data);
        return $this->save();
    }

    public function getAssociationById(string $associationChain, int $id, string $toClassName, int|null $idLanguage = null): array
    {
        $columns = array_values(array_map(fn($x) => $associationChain . '.' . $x, $toClassName::getClassMap()->getAttributesNames()));
        $criteria = static::getCriteria()
            ->select($columns)
            ->where('id', '=', $id);
        if (!is_null($idLanguage)) {
            $criteria->where($associationChain . '.idLanguage', '=', $idLanguage);
        }
        return $criteria
            ->get()
            ->toArray();
    }

    public function retrieveAssociation(string $associationName, int|null $idLanguage = null): void
    {
        $id = $this->getId();
        $associationMap = $this->model::getClassMap()->getAssociationMap($associationName);
        $data = $this->getAssociationById($associationName, $id, $associationMap->toClassName, $idLanguage);
        $toClassName = str_replace("Model", "", str_replace("Models", "Repositories", $associationMap->toClassName));
        $cardinality = $associationMap->cardinality;
        if (count($data) == 0) {
            $this->$associationName = ($cardinality == Association::ONE) ? null : [];
        } else {
            if ($cardinality == Association::ONE) {
                $association = new $toClassName;
                $association->setData($data[0]);
                $this->$associationName = $association;
            } else {
                foreach ($data as $row) {
                    $association = new $toClassName;
                    $association->setData($row);
                    $this->$associationName[$association->getId()] = $association;
                }
            }
        }
    }

    public function saveAssociation(string $associationName): void
    {
        $id = $this->getId();
        $associationMap = $this->model::getClassMap()->getAssociationMap($associationName);
        $cardinality = $associationMap->cardinality;
        if ($cardinality == Association::ONE) {
            $key = $associationMap->fromKey;
            $this->$key = $this->$associationName->getId();
        } else {
//            $associatedIds = [];
//            foreach ($this->$associationName as $associated) {
//                $associatedIds[] = $associated->getId();
//            }
            $associatedIds = array_keys($this->$associationName);
            $this->model->replaceManyToMany($associationName, $id, $associatedIds);
        }
    }

    public function deleteAssociation(string $associationName): void
    {
        $id = $this->getId();
        $associationMap = $this->model::getClassMap()->getAssociationMap($associationName);
        $cardinality = $associationMap->cardinality;
        if ($cardinality == Association::ONE) {
//            $key = $associationMap->fromKey;
//            $this->$key = $this->$associationName->getId();
        } else {
            if ($cardinality == Association::ASSOCIATIVE) {
                $this->model->deleteManyToMany($associationName, $id, null);
            } else if ($cardinality == Association::MANY) {
                $this->retrieveAssociation($associationName);
                $associatedIds = array_keys($this->$associationName);
                $this->model->deleteManyToMany($associationName, $id, $associatedIds);
            }
        }
    }

    public function getAssociation(string $associationName, int|null $idLanguage = null)
    {
        $this->retrieveAssociation($associationName, $idLanguage);
        return $this->$associationName;
    }

    public function retrieveFromCriteria(Criteria $criteria): void
    {
        $data = $criteria->getResult();
        if (!empty($data)) {
            $this->setData($data[0]);
        } else {
            $this->initProperties();
        }
    }

    public function gridDataAsJSON(Criteria $source, $rowsOnly = false, $total = 0): string
    {
//        $data = Manager::getData();
        $result = (object)[
            'rows' => array(),
            'total' => 0
        ];
        if ($source instanceof Criteria) {
            $result->total = $source->asQuery()->count();
            $result->rows = $source->getResult();
        }
//        if ($source instanceof database\mquery) {
//            $result->total = $source->count();
//            if ($data->page > 0) {
//                $source->setRange($data->page, $data->rows);
//            }
//            $result->rows = $source->asObjectArray();
//        } elseif (is_array($source)) {
//            $rows = array();
//            foreach ($source as $row) {
//                $r = new \StdClass();
//                foreach ($row as $c => $col) {
//                    $field = is_numeric($c) ? 'F' . $c : $c;
//                    $r->$field = "{$col}";
//                }
//                $rows[] = $r;
//            }
//            $result->rows = $rows;
//            $result->total = ($total != 0) ? $total : count($rows);
//        }
        if ($rowsOnly) {
            return json_encode($result->rows);
        } else {
            return json_encode($result);
        }
    }

    public function query(string $cmd, string $database = null): ?array
    {
        $database ??= config('database.default');
        //return DB::connection($database)->select($cmd);
        return PersistenceManager::getConnection($database)->select($cmd);
    }

    public static function select(string $cmd, string $database = null): ?array
    {
        $database ??= config('database.default');
        return PersistenceManager::getConnection($database)->select($cmd);
    }

    public function delete()
    {
        $id = $this->getId();
        $this->model->delete($id);
    }

    public static function listAll()
    {
        $self = get_called_class();
        $temp = new $self;
        return $temp->getCriteria()
            ->select('*')
            ->getResult();
    }


}
