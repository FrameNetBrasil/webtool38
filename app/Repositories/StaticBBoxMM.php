<?php

namespace App\Repositories;

use App\Models\StaticBBoxMMModel;
use Maestro\Persistence\Repository;

class StaticBBoxMM extends Repository
{
    public ?int $idStaticBBoxMM;
    public ?int $x;
    public ?int $y;
    public ?int $width;
    public ?int $height;
    public ?int $idStaticObjectMM;
    public ?object $staticObjectMM;

    public function __construct(int $id = null)
    {
        parent::__construct(StaticBBoxMMModel::class, $id);
    }


    public function listByObjectMM($idStaticObjectMM)
    {
        $criteria = $this->getCriteria()
            ->select(['idStaticBBoxMM','x', 'y', 'width', 'height'])
            ->where("idStaticObjectMM = {$idStaticObjectMM}")
            ->orderBy('idStaticBBoxMM');
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


//    public function save($data = null)
//    {
//        $transaction = $this->beginTransaction();
//        try {
//            $this->setData($data);
//            parent::save();
//            Timeline::addTimeline("objectframemm", $this->getId(), "S");
//            $transaction->commit();
//        } catch (\Exception $e) {
//            $transaction->rollback();
//            throw new \Exception($e->getMessage());
//        }
//    }


}
