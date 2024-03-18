<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class LayerGroup extends Repository {

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idLayerGroup');
        if ($filter->idLayerGroup){
            $criteria->where("idLayerGroup LIKE '{$filter->idLayerGroup}%'");
        }
        return $criteria;
    }

    public function listAll(){
        $criteria = $this->getCriteria()->select('idLayerGroup, name')->orderBy('name');
        return $criteria;
    }
}
