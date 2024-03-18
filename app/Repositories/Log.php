<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class Log extends Repository {

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idLog');
        if ($filter->idLog){
            $criteria->where("idLog LIKE '{$filter->idLog}%'");
        }
        return $criteria;
    }
}
