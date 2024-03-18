<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Criteria\Criteria;
use Orkester\Persistence\Repository;

class ViewFrame extends Repository
{
    public static function listByFilter($filter): Criteria
    {
        $listBy = $filter->listBy ?? '';
        $select = ['idFrame','entry','active','idEntity','name','description'];
        if ($listBy == 'cluster') {
            $listBySelect = ',toRelations.toSemanticType.entries.name as cluster';
        }
        if ($listBy == 'type') {
            $listBySelect = ',toRelations.toSemanticType.entries.name as type';
        }
        if ($listBy == 'domain') {
            $listBySelect = ',toRelations.toSemanticType.entries.name as domain';
        }
        $criteria = static::getCriteria()
            ->select($select)
            ->orderBy('entries.name');
//        if ($listBySelect != '') {
//            $criteria->setAssociationType('toRelations', 'left');
//            $criteria->setAssociationType('toRelations.toSemanticType', 'left');
//            $criteria->setAssociationType('toRelations.toSemanticType.entries', 'left');
//            $criteria->where("(toRelations.toSemanticType.entries.idLanguage = {$filter->idLanguage}) or (toRelations.toSemanticType.entries.idLanguage is null)");
//        }
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria->where("idLanguage", "=", $idLanguage);

        if (isset($filter->idFrame)) {
            $criteria->where("idFrame = {$filter->idFrame}");
        }
        if (isset($filter->idEntity)) {
            $criteria->where("idEntity = {$filter->idEntity}");
        }
        if (isset($filter->frame)) {
            $criteria->where("name","startswith",$filter->frame);
        }
        if (isset($filter->lu)) {
            $criteria->distinct(true);
            $criteria->where("lus.name LIKE '{$filter->lu}%'");
            $criteria->where("lus.idLanguage = {$filter->idLanguage}");
        }
        if (isset($filter->idLU)) {
            if (is_array($filter->idLU)) {
                $criteria->where("lus.idLU", "IN", $filter->idLU);
            } else {
                $criteria->where("lus.idLU = {$filter->idLU}");
            }
        }
        if (isset($filter->fe)) {
            $criteria->distinct(true);
            $criteria->where("fes.idLanguage","=",AppService::getCurrentIdLanguage());
            $criteria->where("fes.name","startswith",$filter->fe);
        }
//        if (isset($filter->idDomain)) {
//            Base::relation($criteria, 'ViewFrame', 'Domain', 'rel_hasdomain');
//            $criteria->where("Domain.idDomain = {$filter->idDomain}");
//        }
        if (isset($filter->idFramalDomain)) {
            $criteria->where('relations.relationType.entry', '=', "rel_framal_domain");
            $criteria->where("relations.semanticType2.idSemanticType","=", $filter->idFramalDomain);
        }
        if (isset($filter->idFramalType)) {
            $criteria->where('relations.relationType.entry', '=', "rel_framal_type");
            $criteria->where("relations.semanticType2.idSemanticType","=", $filter->idFramalType);
        }
//        if ($listBy == 'cluster') {
//            $criteria->where('toRelations.relationType', '=', "'rel_framal_cluster'");
//        }
//        if ($listBy == 'type') {
//            $criteria->where('toRelations.relationType', '=', "'rel_framal_type'");
//        }
//        if ($listBy == 'domain') {
//            $criteria->where('toRelations.relationType', '=', "'rel_framal_domain'");
//        }
        debug($criteria->getResult()[0]);
        return $criteria;
    }


}
