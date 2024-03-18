<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class DynamicBBoxMM extends Repository
{
    public function listByObjectMM($idDynamicObjectMM)
    {
        $criteria = $this->getCriteria()
            ->select(['idDynamicBBoxMM', 'frameNumber', 'x', 'y', 'width', 'height', 'frameNumber', 'frameTime', 'blocked'])
            ->where("idDynamicObjectMM", "=", $idDynamicObjectMM)
            ->orderBy('frameNumber');
        return $criteria;
    }

    public function listByObjectsMM(array $idDynamicObjectMM)
    {
        $criteria = $this->getCriteria()
            ->select(['idDynamicBBoxMM', 'idDynamicObjectMM as idObjectMM', 'frameNumber', 'x', 'y', 'width', 'height', 'frameNumber', 'frameTime', 'blocked'])
            ->where("idDynamicObjectMM", "IN", $idDynamicObjectMM)
            ->orderBy('idDynamicBBoxMM,frameNumber');
        return $criteria;
    }

    public function putFrames($idDynamicObjectMM, $frames)
    {
        $this->beginTransaction();
        try {
            $deleteCriteria = $this->getCriteria();
            $deleteCriteria->where("idDynamicObjectMM = {$idDynamicObjectMM}");
            $deleteCriteria->delete();
            foreach ($frames as $row) {
                $frame = (object)$row;
                $this->setPersistent(false);
                $frame->idDynamicObjectMM = $idDynamicObjectMM;
                $this->saveData($frame);
                Timeline::addTimeline("dynamicboxmm", $this->getId(), "S");
            }
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateBBox(array $bbox)
    {
        $this->beginTransaction();
        debug($this);
        try {
            $this->setData($bbox);
            parent::save();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }


    /*
    public function save($data = null)
    {
        $transaction = $this->beginTransaction();
        try {
            $this->setData($data);
            parent::save();
            Timeline::addTimeline("objectframemm", $this->getId(), "S");
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    */


}
