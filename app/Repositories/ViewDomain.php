<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class ViewDomain extends Repository
{
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('idDomain, entry, idEntity, name, idEntityRel, entityType, idLanguage, entryRel, nameRel')->orderBy('name, nameRel');
        $idLanguage = \Manager::getSession()->idLanguage;
        $criteria->where("idLanguage = {$idLanguage}");
        if ($filter->idDomain) {
            $criteria->where("idDomain = {$filter->idDomain}");
        }
        if ($filter->idEntity) {
            $criteria->where("idEntity = {$filter->idEntity}");
        }
        if ($filter->idEntityRel) {
            $criteria->where("idEntityRel = {$filter->idEntityRel}");
        }
        if ($filter->entityType) {
            $criteria->where("entityType = '{$filter->entityType}'");
        }
        return $criteria;
    }

}
