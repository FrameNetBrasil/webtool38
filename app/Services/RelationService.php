<?php

namespace App\Services;

use App\Data\CreateRelationFEInternalData;
use App\Data\RelationData;
use App\Data\UpdateFrameClassificationData;
use App\Http\Controllers\Controller;
use App\Repositories\EntityRelation;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\RelationType;
use App\Repositories\SemanticType;
use App\Repositories\ViewRelation;

class RelationService extends Controller
{
    public static function delete(int $id)
    {
        $relation = new EntityRelation($id);
        $relation->delete();
    }

    public static function newRelation(RelationData $data)
    {
        $relation = new EntityRelation();
        $relation->saveData($data->toArray());
    }

    static public function create(string $relationTypeEntry, int $idEntity1, int $idEntity2, ?int $idEntity3 = null, ?int $idRelation = null)
    {
        $rt = new RelationType();
        $rt->getByEntry($relationTypeEntry);
        $data = RelationData::from([
            'idRelationType' => $rt->idRelationType,
            'idEntity1' => $idEntity1,
            'idEntity2' => $idEntity2,
            'idEntity3' => $idEntity3,
            'idRelation' => $idRelation
        ]);
        self::newRelation($data);
    }

    static public function deleteAll(int $idEntity)
    {
        $er = new EntityRelation();
        $er->removeAllFromEntity($idEntity);
    }

    public static function listRelationsFrame(int $idFrame)
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

    public static function listRelationsFEInternal(int $idFrame)
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

    public static function createRelationFEInternal(CreateRelationFEInternalData $data)
    {
        $idFrameElementRelated = (array)$data->idFrameElementRelated;
        if (count($idFrameElementRelated)) {
            $idFirst = array_shift($idFrameElementRelated);
            $first = new FrameElement($idFirst);
            foreach ($idFrameElementRelated as $idNext) {
                $next = new FrameElement($idNext);
                $relation = new EntityRelation();
                $relation->saveData([
                    'idRelationType' => $data->idRelationType,
                    'idEntity1' => $first->idEntity,
                    'idEntity2' => $next->idEntity,
                ]);
            }
        }
    }

    public static function updateFramalDomain(Frame $frame, UpdateFrameClassificationData $data)
    {
        $relationType = new RelationType();
        $relationType->getByEntry('rel_framal_domain');
        $relation = new EntityRelation();
        $relation->beginTransaction();
        try {
            $relation->removeFromEntityByRelationType($frame->idEntity, $relationType->idRelationType);
            $st = new SemanticType();
            foreach ($data->framalData as $idSemanticType) {
                $st->getbyId($idSemanticType);
                $relation->setPersistent(false);
                $relation->saveData([
                    'idRelationType' => $relationType->idRelationType,
                    'idEntity1' => $frame->idEntity,
                    'idEntity2' => $st->idEntity
                ]);
            }
            $relation->commit();
        } catch (\Exception $e) {
            $relation->rollback();
            throw new \Exception("Error updating relations. " . $e);
        }
    }

    public static function updateFramalType(Frame $frame, UpdateFrameClassificationData $data)
    {
        $relationType = new RelationType();
        $relationType->getByEntry('rel_framal_type');
        $relation = new EntityRelation();
        $relation->beginTransaction();
        try {
            $relation->removeFromEntityByRelationType($frame->idEntity, $relationType->idRelationType);
            $st = new SemanticType();
            foreach ($data->framalData as $idSemanticType) {
                $st->getbyId($idSemanticType);
                $relation->setPersistent(false);
                $relation->saveData([
                    'idRelationType' => $relationType->idRelationType,
                    'idEntity1' => $frame->idEntity,
                    'idEntity2' => $st->idEntity
                ]);
            }
            $relation->commit();
        } catch (\Exception $e) {
            $relation->rollback();
            throw new \Exception("Error updating relations. " . $e);
        }
    }


}
