<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class Access extends Repository {

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idAccess');
        if ($filter->idAccess){
            $criteria->where("idAccess LIKE '{$filter->idAccess}%'");
        }
        return $criteria;
    }
}
