<?php

namespace App\Repositories;

use App\Models\StaticObjectMMModel;
use Maestro\Persistence\Repository;

class StaticObjectMM extends Repository
{

    public ?int $idStaticObjectMM;
    public ?int $scene;
    public ?int $nobdnbox;
    public ?int $idFlickr30kEntitiesChain;
    public ?array $staticBBoxMM;

    public function __construct(int $id = null)
    {
        parent::__construct(StaticObjectMMModel::class, $id);
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idObjectMM');
        if ($filter->idDocumentMM) {
            $criteria->where("idDocumentMM = {$filter->idDocumentMM}");
        }
        if ($filter->status) {
            $criteria->where("status = {$filter->status}");
        }
        if ($filter->origin) {
            $criteria->where("origin = {$filter->origin}");
        }
        return $criteria;
    }

    public function updateObject($data)
    {
        if ($data->idObjectMM != -1) {
            $this->getById($data->idObjectMM);
        }
        $objectFrameMM = new ObjectFrameMM();
        $transaction = $this->beginTransaction();
        try {
            $object = (object)[
                'startTime' => $data->startTime,
                'endTime' => $data->endTime,
                'startFrame' => $data->startFrame,
                'endFrame' => $data->endFrame,
                'idDocumentMM' => $data->idDocumentMM,
                'status' => ($data->idFrameElement > 0) ? 1 : 0,
                'origin' => $data->origin ?: '2',
                'idFrameElement' => $data->idFrameElement,
                'idLU' => $data->idLU,
            ];
            mdump($this->getData());
            $this->save($object);
            Timeline::addTimeline("objectmm", $this->getId(), "S");
            $objectFrameMM->putFrames($this->idObjectMM, $data->frames);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteObjects($idToDelete)
    {
        $transaction = $this->beginTransaction();
        try {
            $objectFrameMM = new ObjectFrameMM();
            $deleteCriteria = $objectFrameMM->getDeleteCriteria();
            $deleteCriteria->where('idObjectMM', 'IN', $idToDelete);
            $deleteCriteria->delete();
            $deleteCriteria = $this->getDeleteCriteria();
            $deleteCriteria->where('idObjectMM', 'IN', $idToDelete);
            $deleteCriteria->delete();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteObjectFrame($idToDelete)
    {
        $transaction = $this->beginTransaction();
        try {
            $objectFrameMM = new ObjectFrameMM();
            $deleteCriteria = $objectFrameMM->getDeleteCriteria();
            $deleteCriteria->where('idObjectFrameMM', '=', $idToDelete);
            $deleteCriteria->delete();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    /*
    public function putObjects($data) {
        $objectFrameMM = new ObjectFrameMM();
        $idAnnotationSetMM = $data->idAnnotationSetMM;
        $transaction = $this->beginTransaction();
        try {
            $selectCriteria = $this->getCriteria()->select('idObjectMM')->where("idAnnotationSetMM = {$idAnnotationSetMM}");
            $deleteFrameCriteria = $objectFrameMM->getDeleteCriteria();
            $deleteFrameCriteria->where("idObjectMM", "IN" , $selectCriteria);
            $deleteFrameCriteria->delete();
            $deleteCriteria = $this->getDeleteCriteria();
            $deleteCriteria->where("idAnnotationSetMM = {$idAnnotationSetMM}");
            $deleteCriteria->delete();
            foreach($data->objects as $object) {
                $this->setPersistent(false);
                $object->idAnnotationSetMM = $data->idAnnotationSetMM;
                mdump($object);
                if ($object->idFrameElement <= 0) {
                    $object->idFrameElement = '';
                    $object->status = 0;
                } else {
                    $object->status = 1;
                }
                $this->save($object);
                $objectFrameMM->putFrames($this->idObjectMM, $object->frames);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    */


    public function save($data = null)
    {
        $transaction = $this->beginTransaction();
        try {
            $this->setData($data);
            parent::save();
            Timeline::addTimeline("objectmm", $this->getId(), "S");
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function getByIdFlickr30k($idFlickr30k)
    {
        $criteria = $this->getCriteria();
        $criteria->where("idFlickr30k", '=', $idFlickr30k);
        $this->retrieveFromCriteria($criteria);
    }


}
