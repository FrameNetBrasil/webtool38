<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class ASComments extends Repository
{

    public function getByAnnotationSet($idAnnotationSet)
    {
        $criteria = $this->getCriteria()->select('*');
        $criteria->where("idAnnotationSet = {$idAnnotationSet}");
        $this->retrieveFromCriteria($criteria);
    }

    public function deleteByAnnotationSet(int $idAnnotationSet)
    {
        $this->getCriteria()
            ->where("idAnnotationSet", "=", $idAnnotationSet)
            ->delete();
    }
}

