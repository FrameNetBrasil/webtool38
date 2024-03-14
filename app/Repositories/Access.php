<?php

namespace App\Repositories;

use App\Models\AccessModel;
use Orkester\Persistence\Repository;

class Access extends Repository {

    public ?int $idAccess;
    public ?int $rights;
    public ?int $idGroup;
    public ?int $idTransaction;
    public ?object $group;
//    public ?object $transaction;

    public function __construct(int $id = null) {
        parent::__construct(AccessModel::class, $id);
    }

    public function getDescription(){
        return $this->getIdAccess();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idAccess');
        if ($filter->idAccess){
            $criteria->where("idAccess LIKE '{$filter->idAccess}%'");
        }
        return $criteria;
    }
}
