<?php

namespace App\Http\Controllers\Structure;

use App\Data\CreateRelationFEData;
use App\Data\CreateRelationFrameData;
use App\Data\RelationData;
use App\Http\Controllers\Controller;
use App\Repositories\EntityRelation;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'auth')]
class RelationController extends Controller
{
    #[Delete(path: '/relation/frame/{idEntityRelation}')]
    public function deleteFrameRelation(string $idEntityRelation)
    {
        try {
            $relation = new EntityRelation($idEntityRelation);
            $relation->delete();
            $this->trigger('reload-gridFrameRelation');
            return $this->renderNotify("success", "Relation deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/relation/feRelation/{idEntityRelation}')]
    public function deleteFEInternalRelation(string $idEntityRelation)
    {
        try {
            $relation = new EntityRelation($idEntityRelation);
            $relation->delete();
            $this->trigger('reload-gridFEInternalRelation');
            return $this->renderNotify("success", "Relation deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/relation/fe/{idEntityRelation}')]
    public function deleteFERelation(int $idEntityRelation)
    {
        try {
            $relation = new EntityRelation($idEntityRelation);
            $relation->delete();
            $this->trigger('reload-gridFERelation');
            return $this->renderNotify("success", "Relation deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }


    #[Post(path: '/relation/frame')]
    public function newFrameRelation()
    {
        try {
            $data = CreateRelationFrameData::from(data('new'));
            $frame = new Frame($data->idFrame);
            $frameRelated = new Frame($data->idFrameRelated);
            $relationData = new RelationData(
                idRelationType: $data->idRelationType,
                idEntity1: ($data->direction == 'd') ? $frame->idEntity : $frameRelated->idEntity,
                idEntity2: ($data->direction == 'd') ? $frameRelated->idEntity : $frame->idEntity,
            );
            RelationService::newRelation($relationData);
            $this->trigger('reload-gridFrameRelation');
            return $this->renderNotify("success", "Relation created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/relation/fe')]
    public function newFERelation()
    {
        try {
            $data = CreateRelationFEData::from(data('relation'));
            $relationBase = new EntityRelation($data->idEntityRelation);
            $fe = new FrameElement($data->idFrameElement);
            $feRelated = new FrameElement($data->idFrameElementRelated);
            $relationData = new RelationData(
                idRelationType: $relationBase->idRelationType,
                idEntity1: $fe->idEntity,
                idEntity2: $feRelated->idEntity,
                idRelation: $data->idEntityRelation
            );
            RelationService::newRelation($relationData);
            $this->trigger('reload-gridFERelation');
            return $this->renderNotify("success", "Relation created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }
}
