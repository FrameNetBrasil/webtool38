<?php

namespace App\Services;

use App\Repositories\AnnotationSet;
use App\Repositories\Base;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\Label;
use App\Repositories\LayerType;
use App\Repositories\Sentence;
use App\Repositories\UserAnnotation;
use App\Repositories\ViewAnnotationSet;
use App\Repositories\WordForm;
use Orkester\Manager;


class CorpusService
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
                $documents = $document->listByFilter((object)['idCorpus' => $idCorpus])->asQuery()->getResult();
                foreach ($documents as $document) {
                    $node = [];
                    $node['id'] = 'd' . $document['idDocument'];
                    $node['type'] = 'document';
                    $node['name'] = $document['name'];
                    $node['state'] = 'closed';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-document';
                    $node['children'] = [];
                    $result[] = $node;
                }
            }
            if ($id[0] == 'd') {
                $idDocument = substr($id, 1);
                $document = new Document($idDocument);
                $sentences = $document->listSentencesForAnnotation();

                $as = new ViewAnnotationSet();

                $userAnnotation = new UserAnnotation();
                $sentenceForAnnotation = $userAnnotation->listSentenceByUser(Base::getCurrentUserId(), $idDocument);
                $hasSentenceForAnnotation = (count($sentenceForAnnotation) > 0);

                foreach ($sentences as $sentence) {

                    if ($hasSentenceForAnnotation) {
                        if (!in_array($sentence['idSentence'], $sentenceForAnnotation)) {
                            continue;
                        }
                    }

                    $targets = $as->listTargetBySentence($sentence['idSentence']);
                    $text = self::decorateSentence($sentence['text'], $targets);

                    $node = [];
                    $node['id'] = 's' . $sentence['idSentence'];
                    $node['type'] = 'sentence';
                    $node['name'] = "[{$sentence['idSentence']}] {$text}";
                    $node['state'] = 'open';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-sentence';
                    $node['children'] = null;
                    $result[] = $node;
                }
            }

        } else {
            $filter = $data;
            $corpus = new Corpus();
            $corpora = $corpus->listByFilter($filter)->asQuery()->getResult();
            foreach ($corpora as $row) {
                $node = [];
                $node['id'] = 'c' . $row['idCorpus'];
                $node['type'] = 'corpus';
                $node['name'] = [$row['name']];
                $node['state'] = 'closed';
                $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-corpus';
                $node['children'] = [];
                $result[] = $node;
            }
        }
        return $result;
    }

    public static function decorateSentence($sentence, $labels)
    {
        $decorated = "";
        $ni = "";
        $i = 0;
        foreach ($labels as $label) {
            //$style = 'background-color:#' . $label['rgbBg'] . ';color:#' . $label['rgbFg'] . ';';
            if ($label['startChar'] >= 0) {
                $decorated .= mb_substr($sentence, $i, $label['startChar'] - $i);
                //$decorated .= "<span style='{$style}'>" . mb_substr($sentence, $label['startChar'], $label['endChar'] - $label['startChar'] + 1) . "</span>";
                $decorated .= "<span class='color_target'>" . mb_substr($sentence, $label['startChar'], $label['endChar'] - $label['startChar'] + 1) . "</span>";
                $i = $label['endChar'] + 1;
            } else { // null instantiation
                $ni .= "<span class='color_target'>" . $label['instantiationType'] . "</span> " . $decorated;
            }
        }
        $decorated = $ni . $decorated . mb_substr($sentence, $i);
        return $decorated;
    }

    public static function listDocumentForGrid(int $idCorpus)
    {
        $result = [];
        $document = new Document();
        $filter = (object)[
            'idCorpus' => $idCorpus
        ];
        $documents = $document->listByFilter($filter)->asQuery()->getResult();
        foreach ($documents as $doc) {
            $node = $doc;
            $node['state'] = 'open';
            $result[] = $node;
        }
        return $result;
    }
}
