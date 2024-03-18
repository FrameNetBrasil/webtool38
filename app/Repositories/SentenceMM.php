<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Repository;

class SentenceMM extends Repository
{
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idSentenceMM');
        if ($filter->idSentence) {
            $criteria->where("idSentenceMM LIKE '{$filter->idSentenceMM}%'");
        }
        if ($filter->idDocumentMM) {
            $criteria->where("idDocumentMM = {$filter->idDocumentMM}");
        }
        if ($filter->origin) {
            $criteria->where("origin = {$filter->origin}");
        }
        return $criteria;
    }

    public function getDocumentData()
    {
        $sentence = new Sentence();
        $sentence->getById($this->getIdSentence());
        $document = $sentence->getParagraph()->getDocument();
        $documentMM = new DocumentMM();
        $documentMM->getByIdDocument($document->getIdDocument());
        $data = (object)[
            'idDocumentMM' => $documentMM->getId(),
            'idDocument' => $document->getId(),
            'videoTitle' => $documentMM->getTitle(),
            //'videoPath' => \Manager::getAppFileURL('', 'files/multimodal/videos/' . $documentMM->getVisualPath(), true),
            'videoPath' => \Manager::getBaseURL() . str_replace('/var/www/html', '', $documentMM->getVideoPath()),
            //'framesPath' => str_replace('.mp4', '', \Manager::getBaseURL() . '/apps/webtool/files/multimodal/videoframes/' . $documentMM->getVisualPath()),
            'videoWidth' => $documentMM->getVideoWidth(),
            'videoHeight' => $documentMM->getVideoHeight(),
        ];
        return $data;
    }


    public function getSentenceObjects()
    {
        $criteria = $this->getCriteria();
        $criteria->select("objectsentencemm.idObjectSentenceMM,
        objectsentencemm.name, objectsentencemm.startChar as startWord, objectsentencemm.endChar as endWord");
        $criteria->where("objectsentencemm.idSentenceMM = {$this->getId()}");
        $criteria->orderBy('objectsentencemm.startChar');
        $objects = $criteria->asQuery()->getResult();
        return $objects;
    }

    public function getObjects()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $viewFrameElement = new ViewFrameElement();
        $criteria = $this->getCriteria();
        $criteria->select("objectsentencemm.idObjectSentenceMM,objectsentencemm.objectmm.idObjectMM, objectsentencemm.objectmm.name,
        objectsentencemm.objectmm.status, objectsentencemm.objectmm.origin, objectsentencemm.objectmm.idFlickr30k,
        objectsentencemm.objectmm.idFrameElement, '' as idFrame, '' as frame, '' as idFE, '' as fe, '' as color");
        $criteria->where("objectsentencemm.idSentenceMM = {$this->getId()}");
        $criteria->orderBy('objectsentencemm.objectmm.idFlickr30k');
        $objects = $criteria->asQuery()->getResult();
        $oMM = [];
        foreach ($objects as $object) {
            //ddump($object);
            if ($object['idFrameElement']) {
                $feCriteria = $viewFrameElement->getCriteria();
                $feCriteria->setAssociationAlias('frame.entries', 'frameEntries');
                $feCriteria->select('idFrame, frameEntries.name as frame, idFrameElement as idFE, entries.name as fe, color.rgbBg as color');
                $feCriteria->where("frameEntries.idLanguage = {$idLanguage}");
                $feCriteria->where("entries.idLanguage = {$idLanguage}");
                $feCriteria->where("idFrameElement = {$object['idFrameElement']}");
                $fe = $feCriteria->asQuery()->getResult()[0];
                $object['idFrame'] = $fe['idFrame'];
                $object['frame'] = $fe['frame'];
                $object['idFE'] = $fe['idFE'];
                $object['fe'] = $fe['fe'];
                $object['color'] = $fe['color'];

            }
            $oMM[] = $object;
        }
        $objects = [];
        $objectFrameMM = new ObjectFrameMM();
        foreach ($oMM as $object) {
            $idObjectMM = $object['idObjectMM'];
            $framesList = $objectFrameMM->listByObjectMM($idObjectMM)->asQuery()->getResult();
            $object['frames'] = $framesList;
            $objects[] = (object)$object;
        }
        return $objects;
    }

    public function getObjectsForAnnotationImage()
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $viewFrameElement = new ViewFrameElement();
        $lu = new LU();
        $criteria = $this->getCriteria();
        $criteria->select(["objectSentenceMM.idObjectSentenceMM","objectSentenceMM.idObjectMM","objectSentenceMM.name",
        "objectSentenceMM.objectMM.status", "objectSentenceMM.objectMM.origin", "objectSentenceMM.objectMM.idFlickr30k",
        "objectSentenceMM.idFrameElement","objectSentenceMM.idLemma","objectSentenceMM.startWord","objectSentenceMM.endWord",
        "'' as idFrame","'' as frame","'' as idFE","'' as fe","'' as color","objectSentenceMM.idLU","'' as lu"]);
        $criteria->where("objectSentenceMM.idSentenceMM","=",$this->getId());
        $criteria->where("objectSentenceMM.idObjectMM","<>",-1);
        //$criteria->orderBy("objectSentenceMM.objectMM.idFlickr30k");
        $criteria->orderBy("objectSentenceMM.startWord");
        $objects = $criteria->asQuery()->getResult();
        $oMM = [];
        foreach ($objects as $object) {
            if ($object['idFrameElement']) {
                $feCriteria = $viewFrameElement->getCriteria();
//                $feCriteria->setAssociationAlias('frame.entries', 'frameEntries');
                $feCriteria->select(["idFrame","frame.name as frame","idFrameElement as idFE","entries.name as fe","color.rgbBg as color"]);
                $feCriteria->where("frame.idLanguage","=",$idLanguage);
                $feCriteria->where("entries.idLanguage","=",$idLanguage);
                $feCriteria->where("idFrameElement","=",$object['idFrameElement']);
                $fe = $feCriteria->asQuery()->getResult()[0];
                $object['idFrame'] = $fe['idFrame'];
                $object['frame'] = $fe['frame'];
                $object['idFE'] = $fe['idFE'];
                $object['fe'] = $fe['fe'];
                $object['color'] = $fe['color'];

            }
            if ($object['idLU'] != '') {
                $lu->getById($object['idLU']);
                $object['lu'] = $lu->name;
            }
            $oMM[] = $object;
        }
        $objects = [];
        $objectFrameMM = new ObjectFrameMM();
        foreach ($oMM as $object) {
            $idObjectMM = $object['idObjectMM'];
            $framesList = $objectFrameMM->listByObjectMM($idObjectMM)->asQuery()->getResult();
            $object['bboxes'] = $framesList;
            $objects[] = (object)$object;
        }
        return $objects;
    }

}
