<?php

namespace App\Repositories;

use App\Models\POSModel;
use Maestro\Persistence\Repository;

class POS extends Repository
{

    public ?int $idPOS;
    public ?string $POS;
    public ?string $entry;
    public ?int $idEntity;
    public ?object $entity;
    public ?array $entries;

    public function __construct(int $id = null)
    {
        parent::__construct(POSModel::class, $id);
    }

    public function getDescription()
    {
        return $this->getIdPOS();
    }

//    public function listAll()
//    {
//        $criteria = $this->getCriteria()->select('idPOS, POS, entry, idEntity')->orderBy('idPOS');
//        return $criteria;
//    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idPOS');
        if ($filter->idPOS) {
            $criteria->where("idPOS LIKE '{$filter->idPOS}%'");
        }
        if ($filter->POS) {
            $criteria->where("POS = upper('{$filter->POS}')");
        }
        return $criteria;
    }

    public function listForCombo()
    {
        $criteria = $this->getCriteria()->select('idPOS, entry.name as name')->orderBy('entry.name');
        Base::entryLanguage($criteria);
        return $criteria;
    }

    public function getByPOS($POS)
    {
        $filter = (object)[
            'POS' => $POS
        ];
        $criteria = $this->listByFilter($filter);
        $this->retrieveFromCriteria($criteria);
    }

    public function save(): ?int
    {
        //Base::entityTimelineSave($this->getIdEntity());
        parent::save();
        Timeline::addTimeline("pos", $this->getId(), "S");
        return $this->getId();
    }

    public function delete()
    {
        Timeline::addTimeline("pos", $this->getId(), "D");
//        Base::entityTimelineDelete($this->getIdEntity());
        parent::delete();
    }


}