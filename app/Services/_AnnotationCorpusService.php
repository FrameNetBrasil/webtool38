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


class AnnotationCorpusService
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

    public static function getLayers($params)
    {
        $layers = [];
        $idSentence = $params->idSentence;
        $sentence = new Sentence();
        $sentence->getById($idSentence);
        $document = $sentence->getAssociation('document', $params->idLanguage);
        $corpus = $document->getAssociation('corpus', $params->idLanguage);
        $layers['metadata'] = [
            'document' => $document->name,
            'corpus' => $corpus->name,
            'sentence' => '#' . $params->idSentence
        ];
        $language = Base::languages()[$params->idLanguage];
        $as = new AnnotationSet();
        // get words/chars
        $wordsChars = $as->getWordsChars($idSentence);
        $words = $wordsChars->words;
        $wordList = [];
        foreach ($words as $i => $word) {
            $words[$i]['hasLU'] = false;
            $wordList[$i] = trim(strtolower($word['word']));
        }
        $wordLU = [];
        $wf = new WordForm();
        $listLUs = $wf->listLU($wordList);
        foreach ($wordList as $i => $word) {
            if (isset($listLUs[$word])) {
                $words[$i]['hasLU'] = true;
                $wordLU[$i] = $listLUs[$word];
            }
        }
        $layers['lus'] = $wordLU;
        $chars = $wordsChars->chars;
        $result = [];
        foreach ($words as $i => $word) {
            $fieldData = $i;
            $result[$fieldData] = (object)[
                'word' => $word['word'],
                'startChar' => $word['startChar'],
                'endChar' => $word['endChar'],
                'hasLU' => $word['hasLU']
            ];
        }
        $layers['words'] = $result;

        $header = "[Sentence: #{$idSentence}] ";
        // get hiddenColumns/frozenColumns/Columns using $words
        $frozenColumns[] = array(
            "field" => "layer",
            "width" => '70',
            "title" => $header,
            "formatter" => ":annotationMethods.cellLayerFormatter",
            "styler" => ":annotationMethods.cellStyler"
        );
        $columns[] = array("field" => "idAnnotationSet", "type" => 'data');
        $columns[] = array("field" => "idLayerType", "type" => 'data');
        $columns[] = array("field" => "idLayer", "type" => 'data');

        // charWidth
        $charWidth = 16;
        if ($language == 'jp') {
            $charWidth = 18;
        }
        if ($language == 'hi') {
            $charWidth = 18;
        }
        if ($language == 'te') {
            $charWidth = 18;
        }
        if ($language == 'kn') {
            $charWidth = 18;
        }
        if ($language == 'zh') {
            $charWidth = 18;
        }
        if ($language == 'fa') {
            $charWidth = 18;
        }

        foreach ($chars as $i => $char) {
            $columns[] = array(
                "type" => "char",
                "width" => $charWidth,
                "title" => $char['char'],
                'order' => $char['offset'],
                'char' => $char['char'],
                'index' => $i,
                'field' => 'c' . $i,
                'word' => $char['order'],
                'hasLU' => $words[$char['order']]['hasLU']
            );
        }
        $layers['columns'] = $columns;
        $layers['frozenColumns'] = $frozenColumns;

        $layers['jsColumns'] = $columns;
        $layers['jsFrozenColumns'] = $frozenColumns;

        // get Layers
        $result = [];
        $asLayers = $as->getLayersSentence($idSentence);
        foreach ($asLayers as $row) {
            $result[$row['idLayer']] = [
                'idAnnotationSet' => $row['idAnnotationSet'],
                'nameLayer' => $row['name'],
                'currentLabel' => '0',
                'currentLabelPos' => 0
            ];
        }

        // CE-FE is a "artificial" layer; it needs to be inserts manually
        $queryLabelType = $as->getLabelTypesCEFE($idSentence);
        $rowsCEFE = $queryLabelType;//->getResult();
        foreach ($rowsCEFE as $row) {
            $result[$row['idLayer']] = [
                'idAnnotationSet' => $row['idAnnotationSet'],
                'nameLayer' => $row['idLayer'],
                'currentLabel' => '0',
                'currentLabelPos' => 0
            ];
        }

        $layers['layers'] = $result;//json_encode($result);

        // get AnnotationSets
        $result = [];
        $annotationSets = $as->getAnnotationSetsBySentence($idSentence);
        foreach ($annotationSets as $row) {
            $result[$row['idAnnotationSet']] = [
                'idAnnotationSet' => $row['idAnnotationSet'],
                'name' => $row['name'],
                'type' => $row['type'],
                'show' => true,
                'annotatedFEs' => (object)[]
            ];
        }
        $layers['annotationSets'] = $result;

        // get LabelTypes
        $result = [];
        $layerLabels = [];
        $layerLabelsTemp = [];

        // GL-GF
        $queryLabelType = $as->getLabelTypesGLGF($idSentence)->asQuery();
        $rows = $queryLabelType->getResult();
        foreach ($rows as $row) {
//            if (!isset($layerLabelsTemp[$row['idLayer']][$row['idLabelType']])) {
//                $layerLabels[$row['idLayer']][] = $row['idLabelType'];
//                $layerLabelsTemp[$row['idLayer']][$row['idLabelType']] = 1;
//            }
            $layerLabels[$row['entry']][$row['idLabelType']] = $row['idLabelType'];
            $result[$row['idLabelType']] = [
                'label' => $row['labelType'],
                'idColor' => $row['idColor'],
                'coreType' => $row['coreType']
            ];
        }
        // GL
        $queryLabelType = $as->getLabelTypesGL($idSentence)->asQuery();
        $rows = $queryLabelType->getResult();
        foreach ($rows as $row) {

//            if (!isset($layerLabelsTemp[$row['idLayer']][$row['idLabelType']])) {
//                $layerLabels[$row['idLayer']][] = $row['idLabelType'];
//                $layerLabelsTemp[$row['idLayer']][$row['idLabelType']] = 1;
//            }
            $layerLabels[$row['entry']][$row['idLabelType']] = $row['idLabelType'];
            $result[$row['idLabelType']] = [
                'label' => $row['labelType'],
                'idColor' => $row['idColor'],
                'coreType' => $row['coreType']
            ];
        }
        // FE
        $queryLabelType = $as->getLabelTypesFE($idSentence);
        $rows = $queryLabelType;//->getResult();
        foreach ($rows as $row) {
//            if (!isset($layerLabelsTemp[$row['idLayer']][$row['idLabelType']])) {
//                $layerLabels[$row['idLayer']][] = $row['idLabelType'];
//                $layerLabelsTemp[$row['idLayer']][$row['idLabelType']] = 1;
//            }
            $layerLabels['lty_fe'][$row['idAnnotationSet']][$row['idLabelType']] = $row['idLabelType'];
            $result[$row['idLabelType']] = [
                'label' => $row['labelType'],
                'idColor' => $row['idColor'],
                'coreType' => str_replace('cty_', '', $row['coreType'])
            ];
        }
        // CE
        $queryLabelType = $as->getLabelTypesCE($idSentence);
        $rows = $queryLabelType;//->getResult();
        foreach ($rows as $row) {
//            if (!isset($layerLabelsTemp[$row['idLayer']][$row['idLabelType']])) {
//                $layerLabels[$row['idLayer']][] = $row['idLabelType'];
//                $layerLabelsTemp[$row['idLayer']][$row['idLabelType']] = 1;
//            }
            $layerLabels['lty_fe'][$row['idAnnotationSet']][$row['idLabelType']] = $row['idLabelType'];
            $result[$row['idLabelType']] = [
                'label' => $row['labelType'],
                'idColor' => $row['idColor'],
                'coreType' => $row['coreType']
            ];
        }
        // CE-FE - $rowsCEFE is obtained via query for layer above
        foreach ($rowsCEFE as $row) {
//            if (!isset($layerLabelsTemp[$row['idLayer']][$row['idLabelType']])) {
//                $layerLabels[$row['idLayer']][] = $row['idLabelType'];
//                $layerLabelsTemp[$row['idLayer']][$row['idLabelType']] = 1;
//            }
            $result[$row['idLabelType']] = [
                'label' => $row['labelType'],
                'idColor' => $row['idColor'],
                'coreType' => $row['coreType']
            ];
        }


        // UDTree
        $UDTreeLayer = [];
        $UDTreeLayer['none'] = '';
        /*
        $queryUDTree = $as->getUDTree($idSentence);
        $rows = $queryUDTree->getResult();
        foreach ($rows as $row) {
            if (!isset($UDTree[$row['idLayer']])) {
                $UDTree[$row['idLayer']][$row['idLabel']] = $row['idLabelParent'];
            }
        }
        */

        $layers['labelTypes'] = $result;
        $layers['layerLabels'] = $layerLabels;
        $layers['UDTreeLayer'] = $UDTreeLayer;

        // get NIs
        $result = [];
        $queryNI = $as->getNI($idSentence, $params->idLanguage);
        $rows = $queryNI;//->getResult();
        foreach ($rows as $row) {
            $result[$row['idLayer']][$row['idLabelType']] = [
                'fe' => $row['feName'],
                'idInstantiationType' => (int)$row['idInstantiationType'],
                'label' => $row['instantiationType'],
                'idColor' => (int)$row['idColor']
            ];
        }
        $layers['nis'] = (count($result) > 0) ? $result : [];
        $layers['data'] = 'null';
        return $layers;
    }

    public static function getLayersData($params)
    {
        $idSentence = $params->idSentence;
        $idAnnotationSet = $params->idAnnotationSet ?? null;

        $as = new AnnotationSet();
//        if (is_null($idAnnotationSet)) {
//            $idLU = $idCxn = NULL;
//        } else {
//            $as->getById($idAnnotationSet);
//            $idLU = $as->getLU()->getIdLU();
//            $idCxn = $as->getCxn()->getIdConstruction();
//        }
//        $isCxn = ($idLU == NULL) && ($idCxn != NULL);
//
//        $result = array();
        // dados de todos os labels de todos os AS
        $labels = $as->getLayersData($idSentence);

        // get the annotationsets - first ordered by target then the other which has no target (cxn)
        $layersOrderedByTarget = $as->getLayersOrderByTarget($idSentence);
        $aSet = [];
        $aTarget = [];
        foreach ($layersOrderedByTarget as $layersOrdered) {
            $aTarget[$layersOrdered['idAnnotationSet']] = 1;
            foreach ($labels as $label) {
                if ($layersOrdered['idAnnotationSet'] == $label['idAnnotationSet']) {
                    $aSet[$label['idAnnotationSet']][] = $label;
                }
            }
        }
        foreach ($labels as $label) {
            if ($aTarget[$label['idAnnotationSet']] == '') {
                $aSet[$label['idAnnotationSet']][] = $label;
            }
        }

        // reorder layers to put Target on top of each annotatioset
        $labels = [];
        $idHeaderLayer = -1;

        foreach ($aSet as $asRows) {
            $hasTarget = false;
            foreach ($asRows as $asRow) {
                if ($asRow['layerTypeEntry'] == 'lty_target') {
                    $asRow['idLayerType'] = 0;
                    $labels[] = $asRow;
                    $hasTarget = true;
                }
            }
            if ($hasTarget) {
                foreach ($asRows as $asRow) {
                    if ($asRow['layerTypeEntry'] != 'lty_target') {
                        $labels[] = $asRow;
                    }
                }
            } else {
                $headerLayer = $asRows[0];
                $headerLayer['layer'] = 'x';
                $headerLayer['startChar'] = -1;
                $headerLayer['idLayerType'] = 0;
                $headerLayer['layerTypeEntry'] = 'lty_as';
                $headerLayer['idLayer'] = $idHeaderLayer--;
                $labels[] = $headerLayer;
                foreach ($asRows as $asRow) {
                    $labels[] = $asRow;
                }
            }
        }
        //mdump($rows);
        // CE-FE
        $ltCEFE = new LayerType();
        $ltCEFE->getByEntry('lty_cefe');
        $queryLabelType = $as->getLayerNameCnxFrame($idSentence);
        $cefe = $queryLabelType;

        $user = Base::getCurrentUser() ?? null;
        $layersToShow = $user ? (session('fnbrLayers') ?? unserialize($user->config)->fnbrLayers) : [];

        $wordsChars = $as->getWordsChars($idSentence);
        $chars = $wordsChars->chars;

        $line = [];
// line for targets
        $line[-1] = (object)[
            'idAnnotationSet' => -1,
            'idLayerType' => -1,
            'layerTypeEntry' => 'lty_all_targets',
            'idLayer' => 0,
            'layer' => '',
            'ni' => 'NI',
            'show' => true,
        ];
        foreach ($chars as $i => $char) {
            $field = 'c' . $i;
            $line[-1]->$field = (object)[
                'char' => $char['char'],
                'idLabelType' => 0,
                'status' => 0,
                'order' => $i
            ];
        }
        $idLayerRef = 0;
        $idAnnotationSetRef = 0;
        $countFELayer = 0;
        $currentIdLayerFE = 0;
        $feChars = [];
        // the loop aggregates labels in Layers
        foreach ($labels as $label) {
            $idLT = $label['idLayerType'];
            if ($idLT != 0) {
                if (!in_array($idLT, $layersToShow)) {
                    continue;
                }
            }
            if ($label['idAnnotationSet'] != $idAnnotationSetRef) {
                $countFELayer = 0;
                $idAnnotationSetRef = $label['idAnnotationSet'];
                $currentIdLayerFE = 0;
                $feChars = [];
            }
            $idLayer = $label['idLayer'];
            if ($idLayer != $idLayerRef) {
                if (($label['layerTypeEntry'] == 'lty_fe') && ($currentIdLayerFE == 0)) {
                    $currentIdLayerFE = $idLayer;
                }
                $line[$idLayer] = new \stdclass();
                // preenche todos os fields com o valor default
                foreach ($chars as $i => $char) {
                    $field = 'c' . $i;
                    $status = 0;
                    if (($label['layerTypeEntry'] == 'lty_gf') || ($label['layerTypeEntry'] == 'lty_pt')) {
                        if (!empty($feChars)) {
                            if (in_array($i, $feChars)) {
                                $status = 1;
                            }
                        }
                    }
                    $line[$idLayer]->$field = (object)[
                        'char' => $char['char'],
                        'idLabelType' => 0,
                        'status' => $status
                    ];
                }
                $lastLayerTypeEntry = $label['layerTypeEntry'];
                $extraFELayer = false;
                if ($lastLayerTypeEntry == 'lty_fe') {
                    ++$countFELayer;
                    if ($countFELayer > 1) {
                        $extraFELayer = true;
                    }
                }
                $line[$idLayer]->idAnnotationSet = (int)$label['idAnnotationSet'];
                $line[$idLayer]->idLayerType = (int)$label['idLayerType'];
                $line[$idLayer]->layerTypeEntry = $label['layerTypeEntry'];
                $line[$idLayer]->idLayer = (int)$idLayer;
                $line[$idLayer]->extraFELayer = $extraFELayer;
                if ($label['idLayerType'] == 0) { // Target
                    $line[$idLayer]->layer = 'AS_' . $label['idAnnotationSet'];
                } else {
                    $line[$idLayer]->layer = $label['layer'];
                }
                $line[$idLayer]->show = true;
                $idLayerRef = $idLayer;
                // if lastLayer=CE, try to add the layers for CE-FE
                if ($lastLayerTypeEntry == 'lty_ce') {
                    foreach ($cefe as $frame) {
                        if ($frame['idAnnotationSet'] == $label['idAnnotationSet']) {
                            $idLayerCEFE = $frame['idLayer'];
                            $line[$idLayerCEFE] = new \stdclass();
                            $line[$idLayerCEFE]->idAnnotationSet = (int)$label['idAnnotationSet'];
                            $line[$idLayerCEFE]->idLayerType = $ltCEFE->getId();
                            $line[$idLayerCEFE]->layerTypeEntry = $idLayerCEFE;
                            $line[$idLayerCEFE]->idLayer = (int)$idLayerCEFE;
                            $line[$idLayerCEFE]->layer = $frame['name'] . '.FE';
                            $line[$idLayerCEFE]->show = true;
                            $cefeData = $as->getCEFEData($idSentence, $idLayerCEFE, $label['idAnnotationSet'])->getResult();
                            foreach ($cefeData as $labelCEFE) {
                                if ($labelCEFE['startChar'] > -1) {
                                    $posChar = $labelCEFE['startChar'];
                                    while ($posChar <= $labelCEFE['endChar']) {
                                        $field = 'c' . $posChar;
                                        $line[$idLayerCEFE]->$field = (object)[
                                            'char' => '',
                                            'idLabelType' => $labelCEFE['idLabelType']
                                        ];
                                        $posChar += 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($label['startChar'] > -1) {
                $posChar = $label['startChar'];
                while ($posChar <= $label['endChar']) {
                    $field = 'c' . $posChar;
                    $line[$idLayer]->$field = (object)[
                        'char' => $chars[$posChar]['char'],
                        'idLabelType' => $label['idLabelType'],
                        'status' => 2
                    ];
                    if ($currentIdLayerFE == $idLayer) {
                        $feChars[] = $posChar;
                    };
                    if ($label['layerTypeEntry'] == 'lty_target') {
                        $line[-1]->$field->idLabelType = $label['idLabelType'];
                        $line[-1]->$field->status = 2;
                    }
                    $posChar += 1;
                }
            }
        }

// last, create data
        $data = [];
        foreach ($line as $layer) {
            if (($idAnnotationSet == 0) || ($idAnnotationSet == $layer->idAnnotationSet)) {
                $data[] = $layer;
            }
        }
//        mdump($data);
        return $data;
    }

    public static function saveLabel(object $data): void
    {
        $label = new Label();
        $label->update($data);
    }

    public static function deleteLabel(object $data): void
    {
        $label = new Label();
        $criteria = $label->getCriteria()
            ->where("idLayer","=",$data->idLayer)
            ->where("startChar","=",$data->startChar);
        $label->retrieveFromCriteria($criteria);
        $label->delete();
    }

    public static function createAnnotationSet(object $data): void
    {
        $as = new AnnotationSet();
        $as->createForLU($data->idSentence, $data->idLU, $data->startChar, $data->endChar);
    }

    public static function deleteAnnotationSet(int $idAnnotationSet): void
    {
        $as = new AnnotationSet($idAnnotationSet);
        $as->delete();
    }

    public static function addFELayer(int $idAnnotationSet): void
    {
        $as = new AnnotationSet($idAnnotationSet);
        $as->addFELayer();
    }
    public static function deleteLastFELayer(int $idAnnotationSet): void
    {
        $as = new AnnotationSet($idAnnotationSet);
        $as->deleteLastFELayer();
    }

}