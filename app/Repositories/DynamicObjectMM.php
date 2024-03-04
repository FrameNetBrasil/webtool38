<?php

namespace App\Repositories;

use App\Models\DynamicObjectMMModel;
use App\Services\AppService;
use Maestro\Persistence\Repository;
use Orkester\Persistence\Enum\Join;

class DynamicObjectMM extends Repository
{
    public ?int $idDynamicObjectMM;
    public ?string $name;
    public ?int $startFrame;
    public ?int $endFrame;
    public ?float $startTime;
    public ?float $endTime;
    public ?int $status;
    public ?int $origin;
    public ?int $idDocument;
    public ?int $idFrameElement;
    public ?int $idLemma;
    public ?int $idLU;
    public ?object $document;
    public ?object $frameElement;
    public ?object $lu;
    public ?object $lemma;

    public function __construct(int $id = null)
    {
        parent::__construct(DynamicObjectMMModel::class, $id);
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

    public function getObjectsByDocument($idDocument)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
//        $viewFrameElement = new ViewFrameElement();
//        $lu = new LU();
//        $criteria = $this->getCriteria();
//        $criteria->select([
//            'idDynamicObjectMM as idObjectMM',
//            'startFrame', 'endFrame',
//            'startTime', 'endTime',
//            'status', 'origin',
//            'idLU', "'' as lu",
////            'idFrameElement', "'' as idFrame", "'' as frame", "'' as idFE", "'' as fe", "'' as color"
//            'idFrameElement', 'frameElement.idFrame', 'frameElement.frame.name as frame', 'idFrameElement as idFE', 'frameElement.name as fe', 'frameElement.color.rgbBg as color'
//        ]);
//        $criteria->where("idDocument","=",$idDocument);
//        $criteria->setAssociationType('frameElement', Join::LEFT);
//        $criteria->orderBy('startTime,endTime');
//        $objects = $criteria->asQuery()->getResult();
        $idLanguage = AppService::getCurrentIdLanguage();
        $cmd = <<<SQL

select do.idDynamicObjectMM as idObjectMM,
       do.startFrame,
       do.endFrame,
       do.startTime,
       do.endTime,
       do.status,
       do.origin,
       do.idLU,
       IF(do.idLU,concat(entries_flu.name,'.',lu.name),'')as lu,
       do.idFrameElement,
       fe.idFrame,
       entries_f.name as frame,
       do.idFrameElement as idFE,
       entries_fe.name as fe,
       color.rgbBg as color
from dynamicobjectmm do
         left join frameelement as fe on do.idFrameElement = fe.idFrameElement
         left join frame as f on fe.idFrame = f.idFrame
         left join entry as entries_f on f.idEntity = entries_f.idEntity
         left join entry as entries_fe on fe.idEntity = entries_fe.idEntity
         left join color on fe.idColor = color.idColor
         left join lu on do.idLU = lu.idLU
         left join frame flu on lu.idFrame = flu.idFrame
         left join entry as entries_flu on flu.idEntity = entries_flu.idEntity
where (do.idDocument = {$idDocument})
and ((entries_f.idLanguage = {$idLanguage}) or (entries_f.idLanguage is null))
and ((entries_fe.idLanguage = {$idLanguage}) or (entries_fe.idLanguage is null))
and ((entries_flu.idLanguage = {$idLanguage}) or (entries_flu.idLanguage is null))
order by do.startTime asc

SQL;
//        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name');
        $result = $this->query($cmd);
        $oMM = [];
        foreach ($result as $i => $row) {
//            $row['order'] = $i + 1;
//            $objects[] = $row;
            //mdump($object);
//            if ($object['idFrameElement']) {
//                $feCriteria = $viewFrameElement->getCriteria();
//                //$feCriteria->setAssociationAlias('frame.entries', 'frameEntries');
//                $feCriteria->select(['idFrame','frame.name as frame','idFrameElement as idFE','name as fe','color.rgbBg as color']);
//                $feCriteria->where("frame.idLanguage","=", $idLanguage);
//                $feCriteria->where("idLanguage","=",$idLanguage);
//                $feCriteria->where("idFrameElement","=",$object['idFrameElement']);
//                $fe = $feCriteria->asQuery()->getResult()[0];
//                $object['idFrame'] = $fe['idFrame'];
//                $object['frame'] = $fe['frame'];
//                $object['idFE'] = $fe['idFE'];
//                $object['fe'] = $fe['fe'];
//                $object['color'] = $fe['color'];
//            }
//            if ($object['idLU']) {
//                $lu->getById($object['idLU']);
//                //$object['lu'] = $lu->getName();
//                $object['lu'] = $lu->getFullName();
//            }
            $oMM[] = $row['idObjectMM'];
        }

//        $objects = [];
        $bboxes = [];
        if (count($result) > 0) {
            $objectFrameMM = new DynamicBBoxMM();
            $bboxList = $objectFrameMM->listByObjectsMM($oMM)->getResult();
            debug($bboxList[0]);
            foreach ($bboxList as $bbox) {
                $bboxes[$bbox['idObjectMM']][] = $bbox;
            }
        }
        $objects = [];
        foreach ($result as $i => $row) {
            $row['order'] = $i + 1;
            $row['bboxes'] = $bboxes[$row['idObjectMM']] ?? [];
            $objects[] = $row;
        }

//        foreach ($oMM as $i => $object) {
//            $idObjectMM = $object['idObjectMM'];
//            $framesList = $objectFrameMM->listByObjectMM($idObjectMM)->asQuery()->getResult();
//            $object['frames'] = $framesList;
//            $object['idObject'] = $i + 1;
//            $object['idObjectClone'] = $object['idObject'];
//            $object['hidden'] = false;
//            $objects[] = (object)$object;
//        }
        return $objects;
    }

    public function updateObject($data)
    {
        if ($data->idObjectMM != -1) {
            $this->getById($data->idObjectMM);
        }
        $documentMM = new DocumentMM($data->idDocumentMM);
        $objectFrameMM = new DynamicBBoxMM();
        $transaction = $this->beginTransaction();
        try {
            $object = (object)[
                'startTime' => $data->startTime,
                'endTime' => $data->endTime,
                'startFrame' => $data->startFrame,
                'endFrame' => $data->endFrame,
                'idDocument' => $documentMM->getIdDocument(),
                'status' => ($data->idFrameElement > 0) ? 1 : 0,
                'origin' => $data->origin ?: '2',
                'idFrameElement' => $data->idFrameElement,
                'idLU' => $data->idLU,
            ];
            mdump($this->getData());
            $this->save($object);
            Timeline::addTimeline("dynamicobjectmm", $this->getId(), "S");
            $objectFrameMM->putFrames($this->idDynamicObjectMM, $data->frames);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateObjectData($data)
    {
        if ($data->idObjectMM != -1) {
            $this->getById($data->idObjectMM);
        }
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
            $objectFrameMM = new DynamicBBoxMM();
            $deleteCriteria = $objectFrameMM->getDeleteCriteria();
            $deleteCriteria->where('idDynamicObjectMM', 'IN', $idToDelete);
            $deleteCriteria->delete();
            $deleteCriteria = $this->getDeleteCriteria();
            $deleteCriteria->where('idDynamicObjectMM', 'IN', $idToDelete);
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
            $objectFrameMM = new DynamicBBoxMM();
            $deleteCriteria = $objectFrameMM->getDeleteCriteria();
            $deleteCriteria->where('idDynamicBBoxMM', '=', $idToDelete);
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


    /*
    public function save($data = null)
    {
        $transaction = $this->beginTransaction();
        try {
            $this->setData($data);
            parent::save();
            Timeline::addTimeline("dynamicobjectmm", $this->getId(), "S");
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    */

    public function getByIdFlickr30k($idFlickr30k)
    {
        $criteria = $this->getCriteria();
        $criteria->where("idFlickr30k", '=', $idFlickr30k);
        $this->retrieveFromCriteria($criteria);
    }


}
