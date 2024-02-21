<?php

namespace App\Services;

use App\Data\RelationData;
use App\Http\Controllers\Controller;
use App\Repositories\EntityRelation;
use App\Repositories\Frame;
use App\Repositories\SemanticType;
use App\Repositories\ViewRelation;

class RelationService extends Controller
{
    public function delete(int $id)
    {
        try {
            $relation = new EntityRelation($id);
            $relation->delete();
            return $this->renderNotify("success", "Relation deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    public static function newRelation(RelationData $data)
    {
        $relation = new EntityRelation();
        $relation->saveData($data->toArray());
    }

    static public function deleteAll(int $idEntity)
    {
        $er = new EntityRelation();
        $er->removeAllFromEntity($idEntity);
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

}
