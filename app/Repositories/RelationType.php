<?php

namespace App\Repositories;

use App\Models\RelationTypeModel;
use App\Services\AppService;
use Maestro\Persistence\Repository;

class RelationType extends Repository
{

    public ?int $idRelationType;
    public ?string $entry;
    public ?string $prefix;
    public ?string $nameEntity1;
    public ?string $nameEntity2;
    public ?string $nameEntity3;
    public ?int $idDomain;
    public ?int $idEntity;
    public ?int $idTypeInstance;
    public ?int $idRelationGroup;
    public ?string $name;
    public ?string $description;
    public ?string $idLanguage;
    public ?object $entity;
    public ?object $typeInstance;
    public ?object $relationGroup;
    public ?array $entries;

    public function __construct(int $id = null)
    {
        parent::__construct(RelationTypeModel::class, $id);
    }

    public function getById(int $id): void
    {
        $criteria = $this->getCriteria()
            ->where('idRelationType', '=', $id)
            ->where('idLanguage','=', AppService::getCurrentIdLanguage());
        $this->retrieveFromCriteria($criteria);
    }

    public function getDescription()
    {
        return $this->getIdRelationType();
    }

    public function getName()
    {
        $criteria = $this->getCriteria()->select('entries.name as name');
        $criteria->where("idRelationType = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->fields('name');
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('idRelationType,entry,entries.name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if (isset($filter->idRelationType)) {
            $criteria->where("idRelationType", "=", $filter->idRelationType);
        }
        if (isset($filter->entry)) {
            $criteria->where("entry", "=", $filter->entry);
        }
        if (isset($filter->group)) {
            $criteria->where("relationGroup.entry", "=", $filter->group);
        }
        return $criteria;
    }

//    public function listAll()
//    {
//        $criteria = $this->getCriteria()->select('idRelationType, entry, nameEntity1, nameEntity2, entries.name as name')->orderBy('entries.name');
//        Base::entryLanguage($criteria);
//        return $criteria;
//    }

    public function getByEntry(string $entry)
    {
        $criteria = $this->getCriteria()
            ->select('*')
            ->where("entry","=",$entry);
        $this->retrieveFromCriteria($criteria);
    }

    public function save(): ?int
    {
        $transaction = $this->beginTransaction();
        try {
            if (!$this->isPersistent()) {
                $entity = new Entity();
                $entity->setAlias($this->getEntry());
                $entity->setType('GT');
                $entity->save();
                $this->setIdEntity($entity->getId());
                $entry = new Entry();
                $entry->newEntry($this->getEntry(), $entity->getId());
                $translation = new Translation();
                $translation->newResource($this->getNameEntity1());
                $translation->newResource($this->getNameEntity2());
            }
            $idRelationType = parent::save();
            $transaction->commit();
            return $idRelationType;
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateEntry($newEntry)
    {
        $transaction = $this->beginTransaction();
        try {
            $entry = new Entry();
            $entry->updateEntry($this->getEntry(), $newEntry);
            $this->setEntry($newEntry);
            parent::save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }


}
