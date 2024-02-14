<?php

namespace App\Repositories;

use App\Models\ViewFrameModel;
use App\Services\AppService;
use Maestro\Persistence\Repository;

class ViewFrame extends Repository
{
    public function __construct(int $id = null) {
        parent::__construct(ViewFrameModel::class, $id);
    }

    public function listByFilter($filter)
    {
        $listBy = $filter->listBy ?? '';
        $listBySelect = '';
        if ($listBy == 'cluster') {
            $listBySelect = ',toRelations.toSemanticType.entries.name as cluster';
        }
        if ($listBy == 'type') {
            $listBySelect = ',toRelations.toSemanticType.entries.name as type';
        }
        if ($listBy == 'domain') {
            $listBySelect = ',toRelations.toSemanticType.entries.name as domain';
        }
        $criteria = $this->getCriteria()
            ->select('idFrame, entry, active, idEntity, name, description' . $listBySelect)
            ->orderBy('entries.name');
        if ($listBySelect != '') {
            $criteria->setAssociationType('toRelations', 'left');
            $criteria->setAssociationType('toRelations.toSemanticType', 'left');
            $criteria->setAssociationType('toRelations.toSemanticType.entries', 'left');
            $criteria->where("(toRelations.toSemanticType.entries.idLanguage = {$filter->idLanguage}) or (toRelations.toSemanticType.entries.idLanguage is null)");
        }
        Base::entryLanguage($criteria);
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
        if (isset($filter->idDomain)) {
            Base::relation($criteria, 'ViewFrame', 'Domain', 'rel_hasdomain');
            $criteria->where("Domain.idDomain = {$filter->idDomain}");
        }
        if ($listBy == 'cluster') {
            $criteria->where('toRelations.relationType', '=', "'rel_framal_cluster'");
        }
        if ($listBy == 'type') {
            $criteria->where('toRelations.relationType', '=', "'rel_framal_type'");
        }
        if ($listBy == 'domain') {
            $criteria->where('toRelations.relationType', '=', "'rel_framal_domain'");
        }
        return $criteria;
    }


}
