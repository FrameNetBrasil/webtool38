<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class ObjectFrameMM extends Repository
{
    public function listByObjectMM($idObjectMM)
    {
        $criteria = $this->getCriteria()
            ->select(["idObjectFrameMM", "frameNumber", "frameTime", "x", "y", "width", "height", "frameNumber", "frameTime", "blocked"])
            ->where("idObjectMM", "=", $idObjectMM)
            ->orderBy('frameNumber');
        return $criteria;
    }

    public function putFrames($idObjectMM, $frames)
    {
        $transaction = $this->beginTransaction();
        try {
            $deleteCriteria = $this->getDeleteCriteria();
            $deleteCriteria->where("idObjectMM = {$idObjectMM}");
            $deleteCriteria->delete();
            foreach ($frames as $row) {
                $frame = (object)$row;
                $this->setPersistent(false);
                $frame->idObjectMM = $idObjectMM;
                $this->setData($frame);
                parent::save();
                Timeline::addTimeline("objectframemm", $this->getId(), "S");
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }


    public function savedata($data = null): ?int
    {
        $transaction = $this->beginTransaction();
        try {
            $this->setData($data);
            parent::save();
            Timeline::addTimeline("objectframemm", $this->getId(), "S");
            $transaction->commit();
            return $this->getId();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }


}
