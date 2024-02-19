<?php

namespace App\Services;

use App\Repositories\Base;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\RelationType;
use App\Repositories\SemanticType;
use App\Repositories\ViewFrame;
use App\Repositories\ViewFrameElement;
use App\Repositories\ViewLU;
use Orkester\Manager;


class FrameService
{

    public static function listForSelect()
    {
        $data = Manager::getData();
        $q = $data->q ?? '';
        $frame = new Frame();
        return $frame->listForSelect($q)->getResult();
    }

    public static function listForTree()
    {
        $data = Manager::getData();
        $result = [];
        $idLanguage = AppService::getCurrentIdLanguage();
        $id = $data->id ?? '';
        if ($id != '') {
            $idFrame = substr($id, 1);
            $icon = config('webtool.fe.icon.tree');
            $frame = new Frame($idFrame);
            $fes = $frame->listFE()->asQuery()->getResult();
            $orderedFe = [];
            foreach ($icon as $i => $j) {
                foreach ($fes as $fe) {
                    if ($fe['coreType'] == $i) {
                        $orderedFe[] = $fe;
                    }
                }
            }
            foreach ($orderedFe as $fe) {
                $node = [];
                $node['id'] = 'e' . $fe['idFrameElement'];
                $node['type'] = 'fe';
                $node['name'] = [$fe['name'], $fe['description']];
                $node['idColor'] = $fe['idColor'];
                $node['state'] = 'open';
                $node['iconCls'] = $icon[$fe['coreType']];
                $node['children'] = null;
                $result[] = $node;
            }
            $lu = new ViewLU();
            $lus = $lu->listByFrame($idFrame, $idLanguage)->asQuery()->getResult();
            foreach ($lus as $lu) {
                $node = [];
                $node['id'] = 'l' . $lu['idLU'];
                $node['type'] = 'lu';
                $node['name'] = [$lu['name'], $lu['senseDescription']];;
                $node['state'] = 'open';
                $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-lu';
                $node['children'] = null;
                $result[] = $node;
            }
        } else {
            $filter = $data;
            if (!(($filter->fe ?? false) || ($filter->lu ?? false))) {
                $frame = new ViewFrame();
                $frames = $frame->listByFilter($filter)->asQuery()->getResult();
                foreach ($frames as $row) {
                    $node = [];
                    $node['id'] = 'f' . $row['idFrame'];
                    $node['type'] = 'frame';
                    $node['name'] = [$row['name'], $row['description']];
                    $node['state'] = 'closed';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-frame';
                    $node['children'] = [];
                    $result[] = $node;
                }
            } else {
                if ($filter->fe ?? false) {
                    $icon = config('webtool.fe.icon.tree');
                    $fe = new ViewFrameElement();
                    $fes = $fe->listByFilter($filter)->asQuery()->getResult();
                    foreach ($fes as $row) {
                        $node = [];
                        $node['id'] = 'e' . $row['idFrame'];
                        $node['type'] = 'feFrame';
                        $node['name'] = [$row['name'], $row['description'], $row['frameName']];
                        $node['state'] = 'closed';
                        $node['iconCls'] = $icon[$row['coreType']];
                        $node['children'] = [];
                        $result[] = $node;
                    }
                } else if ($filter->lu ?? false) {
                    $lu = new ViewLU();
                    $lus = $lu->listByFilter($filter)->asQuery()->getResult();
                    foreach ($lus as $row) {
                        $node = [];
                        $node['id'] = 'l' . $row['idLU'];
                        $node['type'] = 'luFrame';
                        $node['name'] = [$row['name'], $row['senseDescription'], $row['frameName']];
                        $node['state'] = 'closed';
                        $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-lu';
                        $node['children'] = [];
                        $result[] = $node;
                    }
                }
            }
        }
        return $result;
    }

    public static function listFEForGrid(int $idFrame)
    {
        $result = [];
        $frame = new Frame($idFrame);
        $icon = config('webtool.fe.icon.tree');
        $coreness = config('webtool.fe.coreness');
        $fes = $frame->listFE()->asQuery()->getResult();
        $orderedFe = [];
        foreach ($icon as $i => $j) {
            foreach ($fes as $fe) {
                if ($fe['coreType'] == $i) {
                    $orderedFe[] = $fe;
                }
            }
        }
        foreach ($orderedFe as $fe) {
            $node = $fe;
            $node['coreness'] = $coreness[$fe['coreType']];
            $node['state'] = 'open';
            $node['iconCls'] = $icon[$fe['coreType']];
            $result[] = $node;
        }
        return $result;
    }

    public static function listRelations(int $idFrame)
    {
        $frame = new Frame($idFrame);
        $config = config('webtool.relations');
        $result = [];
        $relations = $frame->listDirectRelations();
        foreach ($relations as $relation) {
            $result[] = [
                'idEntityRelation' => $relation['idEntityRelation'],
                'entry' => $relation['entry'],
                'name' => $config[$relation['entry']]['direct'],
                'color' => $config[$relation['entry']]['color'],
                'related' => $relation['name']
            ];
        }
        $relations = $frame->listInverseRelations();
        foreach ($relations as $relation) {
            $result[] = [
                'idEntityRelation' => $relation['idEntityRelation'],
                'entry' => $relation['entry'],
                'name' => $config[$relation['entry']]['inverse'],
                'color' => $config[$relation['entry']]['color'],
                'related' => $relation['name']
            ];
        }
        return $result;
    }

    public static function listFEforSelect(int $idFrame)
    {
        $frame = new Frame($idFrame);
        return $frame->listFE()->asQuery()->getResult();
    }

    public static function listLUforSelect(int $idFrame)
    {
        $frame = new Frame($idFrame);
        return $frame->listLU()->asQuery()->getResult();
    }

    public static function listRelationsFE(int $idEntityRelationBase)
    {
        $frame = new Frame();
        $relations = $frame->listFEDirectRelations($idEntityRelationBase);
        $orderedFe = [];
        $icon = config('webtool.fe.icon.tree');
        $config = config('webtool.relations');
        foreach ($icon as $i => $j) {
            foreach ($relations as $relation) {
                if ($relation['feCoreType'] == $i) {
                    $relation['relationName'] = $config[$relation['entry']]['direct'];
                    $relation['feIconCls'] = $icon[$relation['feCoreType']];
                    $relation['relatedFEIconCls'] = $icon[$relation['relatedFECoreType']];
                    $orderedFe[] = $relation;
                }
            }
        }
        return $orderedFe;
    }


    public static function newRelation(object $data)
    {
        $direction = $data->idRelationType[0];
        $idRelationType = substr($data->idRelationType, 1);
        $frame = new Frame($data->idFrame);
        $frameRelated = new Frame($data->idFrameRelated);
        $relation = new EntityRelation();
        $idEntity1 = ($direction == 'd') ? $frame->idEntity : $frameRelated->idEntity;
        $idEntity2 = ($direction == 'd') ? $frameRelated->idEntity : $frame->idEntity;
        $relation->saveData([
            'idRelationType' => $idRelationType,
            'idEntity1' => $idEntity1,
            'idEntity2' => $idEntity2,
        ]);
    }

    public static function newRelationFE(object $data)
    {
        $relationBase = new EntityRelation($data->idRelation);
        $fe = new FrameElement($data->idFrameElement);
        $feRelated = new FrameElement($data->idFrameElementRelated);
        $relation = new EntityRelation();
        $relation->saveData([
            'idRelationType' => $relationBase->idRelationType,
            'idEntity1' => $fe->idEntity,
            'idEntity2' => $feRelated->idEntity,
            'idRelation' => $data->idRelation,
        ]);
    }

    public static function listInternalRelationsFE(int $idFrame)
    {
        $fe = new FrameElement();
        $config = config('webtool.relations');
        $result = [];
        $icon = config('webtool.fe.icon.tree');
        $relations = $fe->listInternalRelations($idFrame);
        foreach ($relations as $relation) {
            $result[] = [
                'idEntityRelation' => $relation['idEntityRelation'],
                'entry' => $relation['entry'],
                'feName' => $relation['feName'],
                'feIcon' => $icon[$relation['feCoreType']],
                'feIdColor' => $relation['feIdColor'],
                'relatedFEName' => $relation['relatedFEName'],
                'relatedFEIcon' => $icon[$relation['relatedFECoreType']],
                'relatedFEIdColor' => $relation['relatedFEIdColor'],
                'name' => $config[$relation['entry']]['direct'],
                'color' => $config[$relation['entry']]['color'],
            ];
        }
        return $result;
    }

    public static function newInternalRelationFE(object $data)
    {
        $idRelationType = substr($data->idRelationType, 1);
        $idFrameElementRelated = (array)$data->idFrameElementRelated;
        if (count($idFrameElementRelated)) {
            $idFirst = array_shift($idFrameElementRelated);
            $first = new FrameElement($idFirst);
            foreach ($idFrameElementRelated as $idNext) {
                $next = new FrameElement($idNext);
                $relation = new EntityRelation();
                $relation->saveData([
                    'idRelationType' => $idRelationType,
                    'idEntity1' => $first->idEntity,
                    'idEntity2' => $next->idEntity,
                ]);
            }
        }
    }

    public static function newConstraintFE(string $constraintEntry, int $idFEConstrained, int $idFrameConstraint)
    {
        $constraint = Base::createEntity('CN', 'con');
        $feConstrained = new FrameElement($idFEConstrained);
        $frameConstraint = new Frame($idFrameConstraint);
        Base::createConstraintInstance($constraint->idEntity, $constraintEntry, $feConstrained->idEntity, $frameConstraint->idEntity);
    }

    public static function listSemanticTypes(int $idFrame)
    {
        $frame = new Frame($idFrame);
        $semanticType = new SemanticType();
        $relations = $semanticType->listRelations($frame->idEntity, '@framal_type')->getResult();
        return $relations;
    }

    public static function addSemanticType(object $data)
    {
        $frame = new Frame($data->idFrame);
        $semanticType = new SemanticType($data->idSemanticType);
        $semanticType->add($frame->idEntity);
    }

    public static function listFESemanticTypes(int $idFrameElement)
    {
        $frameElement = new FrameElement($idFrameElement);
        $semanticType = new SemanticType();
        $relations = $semanticType->listRelations($frameElement->idEntity, '@ontological_type')->getResult();
        return $relations;
    }

    public static function addFESemanticType(object $data)
    {
        $frameElement = new FrameElement($data->idFrameElement);
        $semanticType = new SemanticType($data->idSemanticType);
        $semanticType->add($frameElement->idEntity);
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

}
