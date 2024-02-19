<?php

namespace App\Services;

use App\Repositories\EntityRelation;
use App\Repositories\LU;
use App\Repositories\SemanticType;
use Orkester\Manager;


class LUService
{

    /*
    public static function listForEvent()
    {
        $data = Manager::getData();
        $q = $data->q ?? '';
        $lu = new LU();
        return $lu->listForEvent($q);
    }
    public static function listSemanticTypes(int $idLU)
    {
        $lu = new LU($idLU);
        $semanticType = new SemanticType();
        $relations = $semanticType->listRelations($lu->idEntity, '@lexical_type')->getResult();
        return $relations;
    }

    public static function addSemanticType(object $data)
    {
        $lu = new LU($data->idLU);
        $semanticType = new SemanticType($data->idSemanticType);
        $semanticType->add($lu->idEntity);
    }

    public static function deleteRelation(int $idEntityRelation)
    {
        $relation = new EntityRelation($idEntityRelation);
        $relation->delete();
    }
    */
}
