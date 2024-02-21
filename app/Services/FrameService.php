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
        foreach ($result as $framal => $values) {
            foreach ($values as $row) {
                $classification[$framal][] = $row['name'];
            }
        }
        $classification['id'][] = "#" . $frame->idFrame;
        return $classification;
    }

}
