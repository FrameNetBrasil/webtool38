<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class AnnotationMM extends Repository
{
    public function getByObjectSentenceMM(int $idObjectSentenceMM) {
        $criteria = $this->getCriteria()
            ->select('*')
            ->where("idObjectSentenceMM","=",$idObjectSentenceMM);
        $this->retrieveFromCriteria($criteria);
    }

}
