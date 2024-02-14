<?php

namespace App\Repositories;

use App\Models\DomainModel;
use Maestro\Persistence\Repository;

class Domain extends Repository
{

    public function __construct(int $id = null) {
        parent::__construct(DomainModel::class, $id);
    }

    public function getDescription()
    {
        return $this->getEntry();
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('entry');
        if ($filter->idDomain) {
            $criteria->where("idDomain = {$filter->idDomain}");
        }
        if ($filter->entry) {
            $criteria->where("entry LIKE '%{$filter->entry}%'");
        }
        return $criteria;
    }

//    public function listAll()
//    {
//        $criteria = $this->getCriteria()->select('idDomain, entries.name as name, idEntity')->orderBy('entries.name');
//        Base::entryLanguage($criteria);
//        return $criteria;
//    }

    public function listForSelection()
    {
        $criteria = $this->getCriteria()->select('idDomain, entries.name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        $criteria->orderBy('entries.name');
        return $criteria;
    }

    public function save(): ?int
    {
        $transaction = $this->beginTransaction();
        try {
            $idEntity = $this->getIdEntity();
            $entity = new Entity($idEntity);
            $entity->setAlias($this->getEntry());
            $entity->setType('DO');
            $entity->save();
            $this->setIdEntity($entity->getId());
            $entry = new Entry();
            $entry->newEntry($this->getEntry(), $entity->getId());
//            Base::entityTimelineSave($this->getIdEntity());
            $idDomain = parent::save();
            Timeline::addTimeline("domain", $this->getId(), "S");
            $transaction->commit();
            return $idDomain;
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function addEntity($idEntity, $relation = 'rel_hasdomain')
    {
        Base::createEntityRelation($idEntity, $relation, $this->getIdEntity());
    }

    public function delDomainFromEntity($idEntity, $idDomainEntity = [], $relation = 'rel_hasdomain')
    {
        $rt = new RelationType();
        $c = $rt->getCriteria()->select('idRelationType')->where("entry = '{$relation}'");
        $er = new EntityRelation();
        $transaction = $er->beginTransaction();
        $criteria = $er->getDeleteCriteria();
        $criteria->where("idEntity1 = {$idEntity}");
        $criteria->where("idEntity2", "IN", $idDomainEntity);
        $criteria->where("idRelationType", "=", $c);
        $criteria->delete();
        $transaction->commit();
    }


}
