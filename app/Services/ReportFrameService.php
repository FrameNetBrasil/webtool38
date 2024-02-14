<?php

namespace App\Services;

use App\Repositories\Base;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\ViewFrame;
use App\Repositories\ViewLU;
use Orkester\Manager;


class ReportFrameService
{

    public static function report(int|string $idFrame, string $lang = ''): array {
        $report = [];
        if ($lang != '') {
            $idLanguage = Base::getIdLanguage($lang);
            session()->now('idLanguage', $idLanguage);
        } else {
            $idLanguage = AppService::getCurrentIdLanguage();
        }
        if (is_numeric($idFrame)) {
            $frame = new Frame($idFrame);
        } else {
            $frame = new Frame();
            $frame->getByName($idFrame);
        }
        $report['frame'] = $frame;
        //$this->data->frame->entry = $frame->getEntryObject();
        $report['fe'] = self::getFEData($frame);
        $report['fecoreset'] = self::getFECoreSet($frame);
        $report['frame']->description = self::decorate($frame->description, $report['fe']['styles']);
        $report['relations'] = self::getRelations($frame);
        $report['classification'] = self::getClassification($frame);
        $report['lus'] = self::getLUs($frame, $idLanguage );
        return $report;
    }

    public static function getFEData($frame): array
    {
        $fes = $frame->listFE()->asQuery()->getResult();
        $core = [];
        $coreun = [];
        $noncore = [];
        $feByEntry = [];
        foreach ($fes as $fe) {
            $feByEntry[$fe['entry']] = $fe;
        }
        $fe = new FrameElement();
        $config = config('webtool.relations');
        $icon = config('webtool.fe.icon.tree');
        $relations = $fe->listInternalRelations($frame->idFrame);
        $relationsByIdFE = [];
        foreach ($relations as $relation) {
            $relationsByIdFE[$relation['feIdFrameElement']][] = [
                'relatedFEName' => $relation['relatedFEName'],
                'relatedFEIcon' => $icon[$relation['relatedFECoreType']],
                'relatedFEIdColor' => $relation['relatedFEIdColor'],
                'name' => $config[$relation['entry']]['direct'],
                'color' => $config[$relation['entry']]['color'],
            ];
        }
        $styles = [];
        foreach ($fes as $fe) {
            $styles[strtolower($fe['name'])] = "color_{$fe['idColor']}";
        }
        foreach ($fes as $fe) {
//            $styles[strtolower($fe['name'])] = "color_{$fe['idColor']}";
//            $frameElement->getById($fe['idFrameElement']);
//            $relations = $this->getRelationsFE($frameElement);
            $fe['relations'] = $relationsByIdFE[$fe['idFrameElement']] ?? [];
//            foreach($relations as $rel => $aRelation) {
//                foreach($aRelation as $relation) {
//                    $fe['relations'][] = [$rel, $feByEntry[$relation]['name'] ?: $relation];
//                }
//            }
            $fe['lower'] = strtolower($fe['name']);
            $fe['description'] = self::decorate($fe['description'], $styles);
            if ($fe['coreType'] == 'cty_core') {
                $core[] = $fe;
            } else if ($fe['coreType'] == 'cty_core-unexpressed') {
                $coreun[] = $fe;
            } else {
                $noncore[] = $fe;
            }
        }
        return [
            'styles' => $styles,
            'core' => $core,
            'core_unexpressed' => $coreun,
            'noncore' => $noncore
        ];
    }

    public static function getFECoreSet($frame): string
    {
        $feCoreSet = $frame->listFECoreSet();
        $s = [];
        foreach($feCoreSet as $i => $cs) {
            $s[$i] = "{" . implode(',', $cs) . "}";
        }
        $result = implode(', ', $s);
        return $result;
    }

    public static function getRelations($frame)
    {
        $relations = [];
        $config = config('webtool.relations');
        $directRelations = $frame->listDirectRelations();
        foreach($directRelations as $row) {
            $relationName = $config[$row['entry']]['direct'];
            $relations[$relationName][$row['idFrame']] = [
                'idFrame' => $row['idFrame'],
                'name' => $row['name'],
                'color' => $config[$row['entry']]['color']
            ];
        }
        $inverseRelations = $frame->listInverseRelations();
        foreach($inverseRelations as $row) {
            $relationName = $config[$row['entry']]['inverse'];
            $relations[$relationName][$row['idFrame']] = [
                'idFrame' => $row['idFrame'],
                'name' => $row['name'],
                'color' => $config[$row['entry']]['color']
            ];
        }
        ksort($relations);
        return $relations;
    }

    public function getRelationsFE($frameElement)
    {
        $relations = [];
        $coreSet = $frameElement->listCoreSet()->asQuery()->getResult();
        $excludes = $frameElement->listExcludes()->asQuery()->getResult();
        $requires = $frameElement->listRequires()->asQuery()->getResult();
        $st = $frameElement->listFE2SemanticType()->asQuery()->getResult();
        foreach($requires as $row) {
            $relations['requires'][] = $row['entry'];
        }
        foreach($excludes as $row) {
            $relations['excludes'][] = $row['entry'];
        }
        foreach($st as $row) {
            $relations['semantic_type'][] = $row['name'];
        }

        return $relations;
    }

    public static function getLUs($frame, $idLanguage)
    {
        $lu = new ViewLU();
        $lus = $lu->listByFrame($frame->idFrame, $idLanguage)->asQuery()->chunkResult('idLU', 'name');
        return $lus;
    }

    public static function getClassification($frame)
    {
        $classification = [];
        $result = $frame->getClassification();
        foreach($result as $framal => $values) {
            foreach($values as $row) {
                $classification[$framal][] = $row['name'];
            }
        }
        $classification['id'][] = "#" . $frame->idFrame;
        return $classification;
    }


    public static function decorate($description, $styles)
    {
        debug($description);
        debug($styles);
        $sentence = utf8_decode($description);
        $decorated = preg_replace_callback(
            "/\#([^\s\.\,\;\?\!\']*)/i",
            function ($matches) use ($styles) {
                $m = substr($matches[0], 1);
                $l = strtolower($m);
//                $s = $styles[utf8_encode($l)];
//                if ($s) {
//                    return "<span class='{$s}'>{$m}</span>";
//                }

                foreach ($styles as $fe => $s) {
                    if (str_contains(utf8_encode($l), $fe)){
                        return "<span class='{$s}'>{$m}</span>";
                    }
                }

//                foreach ($styles as $s) {
//                    $p = strpos(utf8_encode($l), $s['fe']);
//                    if ($p === 0) {
//                        return "<span class='{$s['fe']}'>{$m}</span>";
//                    }
//                }
                return $m;
            },
            $sentence
        );
        return utf8_encode($decorated);
    }



}