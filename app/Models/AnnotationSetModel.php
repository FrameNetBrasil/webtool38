<?php

namespace App\Models;

use App\Services\AppService;
use Orkester\Manager;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Maestro\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class AnnotationSetModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        
        self::table('annotationset');
        self::attribute('idAnnotationSet', key: Key::PRIMARY);
        self::attribute('idSentence', key: Key::FOREIGN);
        self::attribute('idAnnotationStatus', key: Key::FOREIGN);
        self::attribute('idEntityRelated', key: Key::FOREIGN);
//        self::attribute('idEntityLU', field: 'idEntityRelated', type: Type::INTEGER);
//        self::attribute('idEntityCxn', field: 'idEntityRelated', type: Type::INTEGER);
        self::associationMany('lu', model: LUModel::class, keys: 'idEntityRelated:idEntity');
        self::associationMany('cxn', model: ConstructionModel::class, keys: 'idEntityRelated:idEntity');
        self::associationOne('sentence', model: SentenceModel::class, key: 'idSentence');
        self::associationOne('annotationStatus', model: TypeInstanceModel::class, key: 'idAnnotationStatus:idTypeInstance');
        self::associationMany('layers', model: LayerModel::class, keys: 'idAnnotationSet');
    }

    public static function getLU(int $idAnnotationSet): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        return self::getCriteria()
            ->select(['lu.idLU', 'lu.frame.name as frame', 'lu.name', 'lu.senseDescription', 'lu.lemma.pos.POS'])
            ->where('idAnnotationSet', '=', $idAnnotationSet)
            ->where('lu.frame.entries.idLanguage', '=', $idLanguage)
            ->getResult()
            ->first();
    }

    public static function getFEs(int $idAnnotationSet): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        return self::getCriteria()
            ->select([
                'lu.frame.fes.idFrameElement',
                'lu.frame.fes.name as name',
                'lu.frame.fes.coreType',
                'lu.frame.fes.color.rgbFg',
                'lu.frame.fes.color.rgbBg'
            ])
            ->where('idAnnotationSet', '=', $idAnnotationSet)
            ->where('lu.frame.fes.entries.idLanguage', '=', $idLanguage)
            ->getResult()
            ->toArray();
    }

    public static function listSentenceTarget(int $idSentence): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $cmd = <<<HERE
select annotationset.idAnnotationSet                                AS idAnnotationSet,
       entry.name as frame,
       layertype.entry                                              AS layerTypeEntry,
       ifnull(label.startChar, -1)                                  AS startChar,
       ifnull(label.endChar, -1)                                    AS endChar
from
    annotationset
        join lu on (lu.idEntity = annotationset.idEntityRelated)
        join frame on (lu.idFrame = frame.idFrame)
        join entry on (entry.idEntity = frame.idEntity)
        join layer on (annotationset.idAnnotationSet = layer.idAnnotationSet)
        join layertype on (layer.idLayerType = layertype.idLayerType)
        join label on (layer.idLayer = label.idLayer)
        join typeinstance on (label.idInstantiationType = typeinstance.idTypeInstance)
where (annotationset.idSentence = {$idSentence})
and (layertype.entry = 'lty_target')
and (entry.idLanguage = {$idLanguage})
order by label.startChar, annotationset.idAnnotationSet 

HERE;
        mdump($cmd);
        $result = self::getCriteria()->plainSQL($cmd);
        return $result;
    }

    public static function listLayers(int $idAnnotationSet): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $cmd = <<<HERE
select annotationset.idAnnotationSet AS idAnnotationSet,
       layer.idLayer as idLayer,
       layertype.entry as layerTypeEntry,
       entry_lt.name as layerName,
       label.idLabel as idLabel,
       fe.idFrameElement as idFrameElement,
       entry_fe.name as fe,
       ce.idConstructionElement as idConstructionElement,
       entry_ce.name as ce,
       gl.idGenericLabel as idGenericLabel,
       gl.name as gl,
       ifnull(label.startChar, -1) as startChar,
       ifnull(label.endChar, -1) as endChar,
       ifnull(entry_fe.name, ifnull(entry_ce.name, gl.name)) as label,
       ifnull(color_ce.rgbFg, ifnull(color_fe.rgbFg, color_gl.rgbFg)) as rgbFg,
       ifnull(color_ce.rgbBg, ifnull(color_fe.rgbBg, color_gl.rgbBg)) as rgbBg,
       typeinstance.idTypeInstance as idInstantiationType,
       entry_it.name as instantiationType
from 
    annotationset
        join layer on (annotationset.idAnnotationSet = layer.idAnnotationSet)
        join layertype on (layer.idLayerType = layertype.idLayerType)
        join entry entry_lt on (layerType.entry = entry_lt.entry)
        join label on (layer.idLayer = label.idLayer)
        join typeinstance on (label.idInstantiationType = typeinstance.idTypeInstance)
        join entry entry_it on (typeinstance.entry = entry_it.entry)
        left join frameelement fe on (label.idLabelType = fe.idEntity)
        left join color color_fe on (fe.idColor = color_fe.idColor)
        left join entry entry_fe on (fe.idEntity = entry_fe.idEntity)
        left join constructionelement ce on (label.idLabelType = ce.idEntity)
        left join color color_ce on (ce.idColor = color_ce.idColor)
        left join entry entry_ce on (ce.idEntity = entry_ce.idEntity)
        left join genericlabel gl on (label.idLabelType = gl.idEntity)
        left join color color_gl on (gl.idColor = color_gl.idColor)
where annotationset.idAnnotationSet = {$idAnnotationSet}
and (entry_it.idLanguage = {$idLanguage})
and (entry_lt.idLanguage = {$idLanguage})
and ((entry_fe.idLanguage = {$idLanguage}) or (entry_fe.idLanguage is null))
and ((entry_ce.idLanguage = {$idLanguage}) or (entry_ce.idLanguage is null))
and ((gl.idLanguage = {$idLanguage}) or (gl.idLanguage is null))
order by layerType.idLayerType, label.startChar

HERE;
        mdump($cmd);
        $result = self::getCriteria()->plainSQL($cmd);
        return $result;
    }

    public static function getLayerByPosition(int $idAnnotationSet, string $layerType, int $startChar): int
    {
        $layerTypeObject = LayerTypeModel::one(['entry', '=', $layerType]);
        mdump('layerType', $layerType);
        $cmd = <<<HERE
select layer.idLayer
        from annotationset
        join layer on (annotationset.idAnnotationSet = layer.idAnnotationSet)
        join layertype on (layer.idLayerType = layertype.idLayerType)
where (annotationset.idAnnotationSet = {$idAnnotationSet})
and (layertype.entry = '{$layerType}')
HERE;
        mdump($cmd);
        $idLayer = 0;
        $layers = self::getCriteria()->plainSQL($cmd);
        mdump('layers', $layers);
        foreach ($layers as $layer) {
            $cmd = <<<HERE
select label.idLabel
        from label
where (label.idLayer = {$layer['idLayer']})
and (label.startChar = {$startChar})
HERE;
            $labels = self::getCriteria()->plainSQL($cmd);
            mdump('labels', $labels);
            if (count($labels) == 0) {
                $idLayer = $layer['idLayer'];
                break;
            }
        }
        mdump('idLayer', $idLayer);
        if ($idLayer == 0) {
            mdump('NEW idLayer', $idLayer);
            $idLayer = LayerModel::insert([
                'idAnnotationSet' => $idAnnotationSet,
                'idLayerType' => $layerTypeObject['idLayerType'],
                'rank' => 1
            ]);
        }
        return $idLayer;
    }

    public static function create(int $idSentence, int $startChar, int $endChar, int $idLU): array
    {
        $lu = LUModel::find($idLU);
        $idAnnotationSet = AnnotationSetModel::insert([
            'idEntityRelated' => $lu['idEntity'],
            'idSentence' => $idSentence,
            'idAnnotationStatus' => 5
        ]);
        $layerType = LayerTypeModel::one(['entry', '=', 'lty_target']);
        $idLayer = LayerModel::insert([
            'idAnnotationSet' => $idAnnotationSet,
            'idLayerType' => $layerType['idLayerType'],
            'rank' => 1
        ]);
        $idLanguage = AppService::getCurrentIdLanguage();
        $gl = GenericLabelModel::one([
            ['idLanguage', '=', $idLanguage],
            ['name', '=', 'Target']
        ]);
        LabelModel::insert([
            'idLayer' => $idLayer,
            'idLabelType' => $gl['idEntity'],
            'idInstantiationType' => 12,
            'multi' => 0,
            'startChar' => $startChar,
            'endChar' => $endChar
        ]);
        return AnnotationSetModel::find($idAnnotationSet);
    }
}
