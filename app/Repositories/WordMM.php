<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class WordMM extends Repository {

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idWordMM');
        if ($filter->idDocumentMM){
            $criteria->where("idDocumentMM = {$filter->idDocumentMM}");
        }
        if ($filter->origin){
            $criteria->where("origin = {$filter->origin}");
        }
        return $criteria;
    }


}
