<?php

namespace App\Repositories;

use App\Models\ViewLUModel;
use App\Services\AppService;
use Orkester\Persistence\Repository;

class ViewLU extends Repository
{

    public ?int $idLU;
    public ?string $name;
    public ?string $senseDescription;
    public ?int $active;
    public ?int $importNum;
    public ?int $incorporatedFE;
    public ?int $idEntity;
    public ?int $idLemma;
    public ?int $idFrame;
    public ?string $frameEntry;
    public ?string $lemmaName;
    public ?int $idLanguage;
    public ?int $idPOS;
    public ?object $lemma;
    public ?object $frame;
    public ?object $language;
    public ?array $annotationSets;

    public function __construct(int $id = null)
    {
        parent::__construct(ViewLUModel::class, $id);
    }

    public function listByFilter($filter)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria = $this->getCriteria()
            ->select([
                'idLU',
                'name',
                'senseDescription',
                'active',
                'importNum',
                'incorporatedFE',
                'idEntity',
                'idLemma',
                'idFrame',
                'frameEntry',
                'lemmaName',
                'idLanguage',
                'idPOS',
                'frame.name as frameName',
                'language.language'
            ])
            ->where('frame.idLanguage', '=', $idLanguage)
            ->orderBy('name');
        if ($filter->idLU ?? false) {
            $op = is_array($filter->idLU) ? "IN" : "=";
            $criteria->where("idLU", $op, $filter->idFrameElement);
        }
        if ($filter->lu ?? false) {
            $criteria->where("name", "startswith", $filter->lu);
        }
        if ($filter->idLanguage ?? false) {
            $criteria->where("idLanguage", "=", $filter->idLanguage);
        }
        return $criteria;
    }

    public function listByFrame($idFrame, $idLanguage = '', $idLU = NULL)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('name');
        $criteria->where("idFrame = {$idFrame}");
        $criteria->where("idLanguage = {$idLanguage}");
        if ($idLU) {
            if (is_array($idLU)) {
                $criteria->where("idLU", "IN", $idLU);
            } else {
                $criteria->where("idLU = {$idLU}");
            }
        }
        $criteria->orderBy('name');
        return $criteria;
    }

    public function listByFrameToAnnotation($idFrame, $idLanguage = '', $idLU = NULL)
    {
        $criteria = $this->getCriteria()
//            ->select('idLU, name, count(subcorpus.annotationsets.idAnnotationSet) as quant')
            ->select('idLU, name, count(annotationsets.idAnnotationSet) as quant')
            ->where("idFrame = {$idFrame}")
            ->where("idLanguage = {$idLanguage}")
            ->groupBy('idLU,name')
            ->orderBy('name');
        if ($idLU) {
            if (is_array($idLU)) {
                $criteria->where("idLU", "IN", $idLU);
            } else {
                $criteria->where("idLU = {$idLU}");
            }
        }
        return $criteria;
    }

    public function listByLemmaFrame($lemma, $idFrame)
    {
        $criteria = $this->getCriteria()->select('*');
        $criteria->where("idFrame = {$idFrame}");
        $criteria->where("lemmaName = '{$lemma}'");
        return $criteria;
    }

    public function listByIdEntityFrame($idEntityFrame, $idLanguage = '')
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('name');
        $criteria->where("frame.idEntity = {$idEntityFrame}");
        if ($idLanguage != '') {
            $criteria->where("idLanguage = {$idLanguage}");
        }
        return $criteria;
    }

    public function listQualiaRelations($idEntityLU, $idLanguage = '')
    {
        $relation = new ViewRelation();
        $criteria = $relation->getCriteria()->select('relationType, entity1Type, entity2Type, entity3Type, idEntity1, idEntity2, idEntity3');
        $criteria->where("relationGroup = 'rgp_qualia'");
        $criteria->where("idEntity1 = {$idEntityLU}");
        $criteria->setAlias('R');
        $luCriteria = $this->getCriteria()->select('name, R.relationType, R.idEntity2, frame.idEntity idEntityFrame, frame.entries.name nameFrame');
        $luCriteria->joinCriteria($criteria, "R.idEntity2 = idEntity");
        if ($idLanguage != '') {
            $luCriteria->where("idLanguage = {$idLanguage}");
        }
        return $luCriteria;
    }

}

