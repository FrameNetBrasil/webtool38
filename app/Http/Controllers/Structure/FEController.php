<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Repositories\Base;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\LU;
use App\Repositories\Qualia;
use App\Repositories\ViewConstraint;
use App\Services\AppService;
use App\Services\EntryService;
use App\Services\FrameService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'auth')]
class FEController extends Controller
{

    #[Post(path: '/fe')]
    public function newFE()
    {
        try {
            $fe = new FrameElement();
            $fe->create($this->data->new);
            $this->trigger('reload-gridFE');
            return $this->renderNotify("success", "FrameElement created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/fe/{id}/main')]
    public function main(string $id)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $this->data->frameElement = new FrameElement($id);
        $this->data->frameElement->retrieveAssociation("frame", $idLanguage);
        $this->data->_layout = 'page';
        return $this->render("edit");
    }
    #[Get(path: '/fe/{id}/edit')]
    public function edit(string $id)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $this->data->frameElement = new FrameElement($id);
        $this->data->frameElement->retrieveAssociation("frame", $idLanguage);
        $this->data->_layout = 'edit';
        return $this->render("edit");
    }

    #[Delete(path: '/fe/{id}')]
    public function delete(string $id)
    {
        try {
            $fe = new FrameElement($id);
            $fe->delete();
            $this->trigger('reload-gridFE');
            return $this->renderNotify("success", "FrameElement deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/fe/{id}/formEdit')]
    public function formEdit(string $id)
    {
        $this->data->frameElement = new FrameElement($id);
        return $this->render("formEdit");
    }

    #[Put(path: '/fe/{id}')]
    public function update(string $id)
    {
        $frameElement = new FrameElement($id);
        $frameElement->update($this->data->update);
        $this->trigger('reload-gridFE');
        return $this->renderNotify("success", "FrameElement updated.");
    }

    #[Get(path: '/fe/{id}/entries')]
    public function formEntries(string $id)
    {
        $this->data->frameElement = new FrameElement($id);
        $this->data->entries = $this->data->frameElement->listEntries();
        $this->data->languages = AppService::availableLanguages();
        return $this->render("Structure.Entry.main");
    }

    #[Get(path: '/fe/relations/{idEntityRelation}')]
    public function relations(string $idEntityRelation)
    {
        $config = config('webtool.relations');
        $relation = new EntityRelation($idEntityRelation);
        $this->data->idEntityRelation = $idEntityRelation;
        $this->data->idRelationType = $relation->idRelationType;
        $this->data->frame = new Frame();
        $this->data->frame->getByIdEntity($relation->idEntity1);
        $this->data->relatedFrame = new Frame();
        $this->data->relatedFrame->getByIdEntity($relation->idEntity2);
        $this->data->relationName = $config[$relation->entry]['direct'];
        $this->data->title = $this->data->frame->name . " [" . $this->data->relationName . "] " . $this->data->relatedFrame->name;
        return $this->render("relations");
    }

    #[Get(path: '/fe/relations/{idEntityRelation}/formNew')]
    public function relationsFEFormNew(int $idEntityRelation)
    {
        $relation = new EntityRelation($idEntityRelation);
        $this->data->idEntityRelation = $idEntityRelation;
        $this->data->idRelationType = $relation->idRelationType;
        $this->data->frame = new Frame();
        $this->data->frame->getByIdEntity($relation->idEntity1);
        $this->data->relatedFrame = new Frame();
        $this->data->relatedFrame->getByIdEntity($relation->idEntity2);
        $config = config('webtool.relations');
        $this->data->relationName = $config[$relation->entry]['direct'];
        $this->data->relationEntry = $relation->entry;
        return $this->render("Structure.FE.Relation.formNew");
    }

    #[Get(path: '/fe/relations/{idEntityRelation}/grid')]
    public function gridRelationsFE(int $idEntityRelation)
    {
        $relation = new EntityRelation($idEntityRelation);
        $this->data->idEntityRelation = $idEntityRelation;
        $this->data->idRelationType = $relation->idRelationType;
        $this->data->frame = new Frame();
        $this->data->frame->getByIdEntity($relation->idEntity1);
        $this->data->relatedFrame = new Frame();
        $this->data->relatedFrame->getByIdEntity($relation->idEntity2);
        $config = config('webtool.relations');
        $this->data->relationName = $config[$relation->entry]['direct'];
        $this->data->relationEntry = $relation->entry;
        $this->data->relations = FrameService::listRelationsFE($idEntityRelation);
        return $this->render("Structure.FE.Relation.grid");
    }

    #[Post(path: '/fe/{idEntityRelation}/relations')]
    public function newRelationFE($idEntityRelation)
    {
        try {
            $this->data->relation->idRelation = $idEntityRelation;
            FrameService::newRelationFE($this->data->relation);
            $this->trigger('reload-gridRelationFE');
            return $this->renderNotify("success", "Relation created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/fe/relations/{idEntityRelation}')]
    public function deleteRelation(int $idEntityRelation)
    {
        try {
            FrameService::deleteRelation($idEntityRelation);
            $this->trigger('reload-gridRelationFE');
            return $this->renderNotify("success", "Relation deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/fe/{id}/constraints')]
    public function constraints(string $id)
    {
        $this->data->idFrameElement = $id;
        return $this->render("Structure.FE.Constraint.child");
    }

    #[Get(path: '/fe/{id}/constraints/formNew/{fragment?}')]
    public function constraintsFormNew(int $id, ?string $fragment = null)
    {
        $this->data->idFrameElement = $id;
        $this->data->frameElement = new FrameElement($id);
        $this->data->fragment = $fragment;
        return $this->render("Structure.FE.Constraint.formNew", $fragment);
    }

    #[Get(path: '/fe/{id}/constraints/grid')]
    public function constraintsGrid(int $id)
    {
        $this->data->idFrameElement = $id;
        $fe = new FrameElement($id);
        $constraint = new ViewConstraint();
        $this->data->constraints = $constraint->listByIdConstrained($fe->idEntity);
        return $this->render("Structure.FE.Constraint.grid");
    }

    #[Post(path: '/fe/{id}/constraints')]
    public function constraintsNew($id)
    {
        try {
            $this->data->idFrameElement = $id;
            if ($this->data->constraint == 'rel_constraint_frame') {
                FrameService::newConstraintFE($this->data->constraint, $id, $this->data->idFrameConstraint);
            } else if ($this->data->constraint == 'rel_qualia') {
                $fe = new FrameElement($id);
                $feQualia = new FrameElement($this->data->idFEQualiaConstraint);
                $qualia = new Qualia($this->data->idQualiaConstraint);
                Base::createEntityRelation($fe->idEntity, $this->data->constraint, $feQualia->idEntity, $qualia->idEntity);
            } else if ($this->data->constraint == 'rel_festandsforfe') {
                $fe = new FrameElement($id);
                $feMetonym = new FrameElement($this->data->idFEMetonymConstraint);
                Base::createEntityRelation($fe->idEntity, $this->data->constraint, $feMetonym->idEntity);
            } else if ($this->data->constraint == 'rel_festandsforlu') {
                $fe = new FrameElement($id);
                $luMetonym = new LU($this->data->idLUMetonymConstraint);
                Base::createEntityRelation($fe->idEntity, $this->data->constraint, $luMetonym->idEntity);
            }
            $this->trigger('reload-gridConstraintFE');
            return $this->renderNotify("success", "Constraint created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/fe/constraints/{idEntityRelation}')]
    public function deleteConstraint(int $idEntityRelation)
    {
        try {
            FrameService::deleteRelation($idEntityRelation);
            $this->trigger('reload-gridConstraintFE');
            return $this->renderNotify("success", "Constraint deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/fe/{id}/semanticTypes')]
    public function semanticTypes(string $id)
    {
        $fe = new FrameElement($id);
        $this->data->idEntity = $fe->idEntity;
        $this->data->root = "@ontological_type";
        return $this->render("Structure.SemanticType.child");
    }

}
