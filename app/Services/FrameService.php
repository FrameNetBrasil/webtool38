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
