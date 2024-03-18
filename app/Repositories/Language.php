<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class Language extends Repository {

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idLanguage');
        if ($filter->idLanguage){
            $criteria->where("idLanguage = {$filter->idLanguage}");
        }
        if ($filter->language){
            $criteria->where("language LIKE '{$filter->language}%'");
        }
        return $criteria;
    }

    public function listForSelection(){
        $criteria = $this->getCriteria()
            ->select(['idLanguage','language','description',"concat(language,' - ',description) as ldescription"])
            ->where("idLanguage","<>",0)
            ->orderBy('language');
        return $criteria;
    }

    public function getByLanguage($language){
        $criteria = $this->getCriteria()->select('*')->where("language = '{$language}'");
        $this->retrieveFromCriteria();

    }

}
