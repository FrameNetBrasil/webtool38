<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Repository;

class ViewRelation extends Repository
{

    public function listByType($relationType, $entity1Type, $entity2Type = '', $idEntity1 = '', $idEntity2 = '')
    {
        $criteria = $this->getCriteria()->select('relationType, entity1Type, entity2Type, entity3Type, idEntity1, idEntity2, idEntity3');
        $criteria->where("relationType = '{$relationType}'");
        $criteria->where("entity1Type = '{$entity1Type}'");
        if ($entity2Type != '') {
            $criteria->where("entity2Type = '{$entity2Type}'");
        }
        if ($idEntity1 != '') {
            $criteria->where("idEntity1 = {$idEntity1}");
        }
        if ($idEntity2 != '') {
            $criteria->where("idEntity2 = {$idEntity2}");
        }
        return $criteria;
    }

    public function listForFrameGraph(int $idEntity): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        return $this->getCriteria()
            ->select(['idEntityRelation','idRelationType', 'relationType.entry', 'entity1Type', 'entity2Type', 'entity3Type', 'idEntity1', 'idEntity2', 'idEntity3',
                'frame1.name frame1Name',
                'frame2.name frame2Name',
            ])
            ->where('entity1Type', '=', 'FR')
            ->where('entity2Type', '=', 'FR')
            ->where('frame1.idLanguage', '=', $idLanguage)
            ->where('frame2.idLanguage', '=', $idLanguage)
            ->whereRaw("((idEntity1 = {$idEntity}) or (idEntity2 = {$idEntity}))")
            ->getResult();
    }

    /*
     * Remove rel_inheritance_cxn
    */
    public function deleteInheritanceCxn($idEntityRelation)
    {
        $transaction = $this->beginTransaction();
        try {
            $cmd = <<<HERE
DELETE FROM entityrelation
WHERE idEntityRelation = {$idEntityRelation}

HERE;
            $this->getDb()->executeCommand($cmd);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \exception($e->getMessage());
        }

    }


}

