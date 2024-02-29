<?php

namespace App\Repositories;

use App\Models\DynamicBBoxMMModel;
use Maestro\Persistence\Repository;

class DynamicBBoxMM extends Repository
{
    public ?int $idDynamicBBoxMM;
    public ?int $frameNumber;
    public ?float $frameTime;
    public ?int $x;
    public ?int $y;
    public ?int $width;
    public ?int $height;
    public ?int $blocked;
    public ?int $idDynamicObjectMM;

    public function __construct(int $id = null)
    {
        parent::__construct(DynamicBBoxMMModel::class, $id);
    }


    public function listByObjectMM($idDynamicObjectMM)
    {
        $criteria = $this->getCriteria()
            ->select(['idDynamicBBoxMM', 'frameNumber', 'x', 'y', 'width', 'height', 'frameNumber', 'frameTime', 'blocked'])
            ->where("idDynamicObjectMM", "=", $idDynamicObjectMM)
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
