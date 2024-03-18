<?php
namespace App\Repositories;

use Orkester\Persistence\Repository;

class Paragraph extends Repository {
    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idParagraph');
        if ($filter->idParagraph){
            $criteria->where("idParagraph LIKE '{$filter->idParagraph}%'");
        }
        return $criteria;
    }
}
