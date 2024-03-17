<?php

namespace App\Services;

use App\Repositories\Base;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\StaticAnnotationMM;
use App\Repositories\StaticBBoxMM;
use App\Repositories\StaticObjectSentenceMM;
use App\Repositories\StaticSentenceMM;
use App\Repositories\UserAnnotation;
use App\Repositories\Timeline;
use Orkester\Persistence\Model;
use Orkester\Manager;

class AnnotationStaticFrameMode2Service
{
    public static function listForTree()
    {
        $data = Manager::getData();
        $id = $data->id ?? '';
        $result = [];
        if ($id != '') {
            if ($id[0] == 'c') {
                $idCorpus = substr($id, 1);
                $document = new Document();
                $filter = $data;
                $filter->idCorpus = $idCorpus;
                $filter->flickr30k = 4;
                $documents = $document->listByFilter($filter)->asQuery()->getResult();
                foreach ($documents as $document) {
                    $node = [];
                    $node['id'] = 'd' . $document['idDocument'];
                    $node['type'] = 'document';
                    $node['name'] = $document['name'];
                    $node['idSentence'] = '';
                    $node['idSentenceMM'] = '';
                    $node['image'] = '';
                    $node['status'] = '';
                    $node['state'] = 'closed';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-document';
                    $node['children'] = [];
                    $result[] = $node;
                }
            }
            if ($id[0] == 'd') {
                $idDocument = substr($id, 1);
                $staticSentenceMM = new StaticSentenceMM();
                $sentences = $staticSentenceMM->listByDocument($idDocument);
                $userAnnotation = new UserAnnotation();
                $sentenceForAnnotation = $userAnnotation->listSentenceByUser(Base::getCurrentUserId(), $idDocument);
                $hasSentenceForAnnotation = (count($sentenceForAnnotation) > 0);
                foreach ($sentences as $sentence) {

                    if ($hasSentenceForAnnotation) {
                        if (!in_array($sentence['idSentence'], $sentenceForAnnotation)) {
                            continue;
                        }
                    }
                    $node = [];
                    $node['id'] = 's' . $sentence['idStaticSentenceMM'];
                    $node['type'] = 'sentence';
                    $node['name'] = $sentence['text'];
                    $node['idSentence'] = $sentence['idSentence'];
                    $node['idStaticSentenceMM'] = $sentence['idStaticSentenceMM'];
                    $node['image'] = $sentence['image'];
                    $node['status'] = $sentence['status'];
                    $node['state'] = 'open';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-annotation-static-frame-mode-1';
                    $node['children'] = null;
                    $result[] = $node;
                }
            }

        } else {
            $filter = $data;
            $corpus = new Corpus();
            $filter->flickr30k = 4;
            $corpora = $corpus->listByFilter($filter)->asQuery()->getResult();
            foreach ($corpora as $row) {
                $node = [];
                $node['id'] = 'c' . $row['idCorpus'];
                $node['type'] = 'corpus';
                $node['name'] = [$row['name']];
                $node['idSentence'] = '';
                $node['idSentenceMM'] = '';
                $node['image'] = '';
                $node['status'] = '';
                $node['state'] = 'closed';
                $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-corpus';
                $node['children'] = [];
                $result[] = $node;
            }
        }
        return $result;
    }

    public static function getPrevious(int $idStaticSentenceMM)
    {
        $cmd = "
select max(sm.idStaticSentenceMM) i
from StaticSentenceMM sm
where (idStaticSentenceMM < {$idStaticSentenceMM})
and (idDocument = (select idDocument from StaticSentenceMM where idStaticSentenceMM = {$idStaticSentenceMM}))
        ";
        return Model::select($cmd)[0]['i'];
    }

    public static function getNext(int $idStaticSentenceMM)
    {
        $cmd = "
select min(sm.idStaticSentenceMM) i
from StaticSentenceMM sm
where (idStaticSentenceMM > {$idStaticSentenceMM})
and (idDocument = (select idDocument from StaticSentenceMM where idStaticSentenceMM = {$idStaticSentenceMM}))
        ";
        return Model::select($cmd)[0]['i'];
    }

    public static function getObjectsForAnnotationImage(int $idStaticSentenceMM): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $staticObjectSentenceMM = new StaticObjectSentenceMM();
        $criteria = $staticObjectSentenceMM->getCriteria()
            ->where("idStaticSentenceMM", "=", $idStaticSentenceMM)
            ->select([
                "idStaticObjectSentenceMM",
                "idStaticObjectMM",
                "name",
                "staticObjectMM.idFlickr30kEntitiesChain",
                "startWord",
                "endWord"
            ])
            ->orderBy("idStaticObjectSentenceMM");
        $objects = $criteria->get()->keyBy('idStaticObjectSentenceMM')->all();
        $bboxMM = new StaticBBoxMM();
        foreach ($objects as $i => $object) {
            $idStaticObjectMM = $object['idStaticObjectMM'];
            $boxes = $bboxMM->listByObjectMM($idStaticObjectMM)->asQuery()->getResult();
            $objects[$i]['bboxes'] = $boxes;
        }
        $frames = [];
        $annotationMM = new StaticAnnotationMM();
        foreach ($objects as $object) {
            $criteria = $annotationMM->getCriteria()
                ->where("idStaticObjectSentenceMM", "=", $object['idStaticObjectSentenceMM'])
                ->where("frame.idLanguage", "=", $idLanguage)
                ->select([
                    "idStaticAnnotationMM",
                    "idStaticObjectSentenceMM",
                    "idFrame",
                    "frame.name as frameName",
                    "idFrameElement"
                ]);
            $criteria->order("idFrame");
            $annotations = $criteria->asQuery()->getResult();

            foreach ($annotations as $annotation) {
                if (!isset($frames[$annotation['idFrame']])) {
                    $frames[$annotation['idFrame']] = [
                        'idFrame' => $annotation['idFrame'],
                        'name' => $annotation['frameName'],
                        'objects' => []
                    ];
                }
                if (is_null($annotation['idFrameElement'])) {
                    $annotation['idFrameElement'] = -1;
                }
                $frames[$annotation['idFrame']]['objects'][$annotation['idStaticObjectSentenceMM']] = $annotation;
            }
        }
        return [
            'objects' => $objects,
            'frames' => $frames
        ];
    }

    public static function hasFrame(int $idStaticSentenceMM, int $idFrame)
    {
        // verifica se o frame já não foi adicionado antes
        $annotationMM = new StaticAnnotationMM();
        $criteria = $annotationMM->getCriteria()
            ->where("staticObjectSentenceMM.idStaticSentenceMM", "=", $idStaticSentenceMM)
            ->where("idFrame", "=", $idFrame)
            ->select('*');
        $annotations = $criteria->asQuery()->getResult();
        return (count($annotations) > 0);
    }

    public static function deleteAnnotationByFrame(int $idStaticSentenceMM, int $idFrame)
    {
        $annotationMM = new StaticAnnotationMM();
        $criteria = $annotationMM->getCriteria()
            ->where("staticObjectSentenceMM.idStaticSentenceMM", "=", $idStaticSentenceMM)
            ->where("idFrame", "=", $idFrame)
            ->select('idStaticAnnotationMM');
        $annotations = $criteria->get()->pluck('idStaticAnnotationMM')->all();
        if (!empty($annotations)) {
            $annotationMM->getCriteria()
                ->where("idStaticAnnotationMM", "IN", $annotations)
                ->delete();
        }
    }

    public static function updateObjectSentenceFE(int $idStaticSentenceMM, int $idFrame, array $staticObjectSentenceFEs)
    {
        self::deleteAnnotationByFrame($idStaticSentenceMM, $idFrame);
        $annotationMM = new StaticAnnotationMM();
        foreach ($staticObjectSentenceFEs as $objects) {
            foreach ($objects as $idStaticObjectSentenceMM => $idFrameElement) {
                if ($idFrameElement == -1) {
                    $idFrameElement = null;
                }
                $data = [
                    'idStaticAnnotationMM' => null,
                    'idStaticObjectSentenceMM' => $idStaticObjectSentenceMM,
                    'idFrameElement' => $idFrameElement,
                    'idFrame' => $idFrame,
                ];
                $annotationMM->saveData($data);
                Timeline::addTimeline("staticannotationmm", $annotationMM->getId(), "C");
            }
        }
    }


}
