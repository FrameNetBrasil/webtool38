<?php
namespace App\Repositories;

use App\Models\ObjectMMModel;
use Maestro\Persistence\Repository;

class ObjectMM extends Repository {

    public ?int $idObjectMM;
    public ?string $name;
    public ?int $startFrame;
    public ?int $endFrame;
    public ?int $startTime;
    public ?int $endTime;
    public ?string $status;
    public ?int $origin;
    public ?int $idDocumentMM;
    public ?int $idFrameElement;
    public ?int $idFlickr30k;
    public ?int $idImageMM;
    public ?int $idLemma;
    public ?int $idLU;
    public ?object $documentMM;
    public ?object $frameElement;
    public ?object $lu;
    public ?object $lemma;
    public function __construct(int $id = null)
    {
        parent::__construct(ObjectMMModel::class, $id);
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idObjectMM');
        if ($filter->idDocumentMM){
            $criteria->where("idDocumentMM = {$filter->idDocumentMM}");
        }
        if ($filter->status){
            $criteria->where("status = {$filter->status}");
        }
        if ($filter->origin){
            $criteria->where("origin = {$filter->origin}");
        }
        return $criteria;
    }

    public function updateObject($data) {
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
            ddump($this->getData());
            $this->save($object);
            Timeline::addTimeline("objectmm",$this->getId(),"S");
            $objectFrameMM->putFrames($this->idObjectMM, $data->frames);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteObjects($idToDelete) {
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

    public function deleteObjectFrame($idToDelete) {
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
                ddump($object);
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
            Timeline::addTimeline("objectmm",$this->getId(),"S");
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function getByIdFlickr30k($idFlickr30k) {
        $criteria = $this->getCriteria();
        $criteria->where("idFlickr30k",'=', $idFlickr30k);
        $this->retrieveFromCriteria($criteria);
    }
    
    
}
