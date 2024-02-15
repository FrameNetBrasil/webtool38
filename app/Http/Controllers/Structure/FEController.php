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

    #[Post(path: '/fes')]
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

    #[Get(path: '/fes/{idFrameElement}/edit')]
    public function edit(string $idFrameElement)
    {
        $this->data->frameElement = new FrameElement($idFrameElement);
        return $this->render("pageEdit");
    }

    #[Delete(path: '/fes/{idFrameElement}')]
    public function delete(string $idFrameElement)
    {
        try {
            $fe = new FrameElement($idFrameElement);
            $fe->delete();
            $this->trigger('reload-gridFE');
            return $this->renderNotify("success", "FrameElement deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/fes/{idFrameElement}/formEdit')]
    public function formEdit(string $idFrameElement)
    {
        $this->data->frameElement = new FrameElement($idFrameElement);
        return $this->render("formEdit");
    }

    #[Put(path: '/fes/{idFrameElement}')]
    public function update(string $idFrameElement)
    {
        $frameElement = new FrameElement($idFrameElement);
        $frameElement->update($this->data->update);
        $this->trigger('reload-gridFE');
        return $this->renderNotify("success", "FrameElement updated.");
    }

    #[Get(path: '/fes/{idFrameElement}/entries')]
    public function formEntries(string $idFrameElement)
    {
        $this->data->frameElement = new FrameElement($idFrameElement);
        $this->data->entries = $this->data->frameElement->listEntries();
        $this->data->languages = AppService::availableLanguages();
        return $this->render("entries");
    }

    #[Put(path: '/fes/{idFrame}/entries')]
    public function entries(int $idFrame)
    {
        try {
            EntryService::updateEntries($this->data);
            return $this->renderNotify("success", "Translations recorded.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/fes/relations/{idEntityRelation}')]
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

    #[Get(path: '/fes/relations/{idEntityRelation}/formNew')]
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

    #[Get(path: '/fes/relations/{idEntityRelation}/grid')]
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

    #[Post(path: '/fes/{idEntityRelation}/relations')]
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

    #[Delete(path: '/fes/relations/{idEntityRelation}')]
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

    #[Get(path: '/fes/{idFrameElement}/constraints')]
    public function constraints(string $idFrameElement)
    {
        $this->data->idFrameElement = $idFrameElement;
        return $this->render("constraints");
    }

    #[Get(path: '/fes/{idFrameElement}/constraints/formNew/{fragment?}')]
    public function constraintsFormNew(int $idFrameElement, ?string $fragment = null)
    {
        $this->data->idFrameElement = $idFrameElement;
        $this->data->frameElement = new FrameElement($idFrameElement);
        $this->data->fragment = $fragment;
        return $this->render("Structure.FE.Constraint.formNew", $fragment);
    }

    #[Get(path: '/fes/{idFrameElement}/constraints/grid')]
    public function constraintsGrid(int $idFrameElement)
    {
        debug($this->data);
        $this->data->idFrameElement = $idFrameElement;
        $fe = new FrameElement($idFrameElement);
        $constraint = new ViewConstraint();
        $this->data->constraints = $constraint->listByIdConstrained($fe->idEntity);
        return $this->render("Structure.FE.Constraint.grid");
    }

    #[Post(path: '/fes/{idFrameElement}/constraints')]
    public function constraintsNew($idFrameElement)
    {
        try {
            debug($this->data);
            $this->data->idFrameElement = $idFrameElement;
            if ($this->data->constraint == 'rel_constraint_frame') {
                FrameService::newConstraintFE($this->data->constraint, $idFrameElement, $this->data->idFrameConstraint);
            } else if ($this->data->constraint == 'rel_qualia') {
                $fe = new FrameElement($idFrameElement);
                $feQualia = new FrameElement($this->data->idFEQualiaConstraint);
                $qualia = new Qualia($this->data->idQualiaConstraint);
                Base::createEntityRelation($fe->idEntity, $this->data->constraint, $feQualia->idEntity, $qualia->idEntity);
            } else if ($this->data->constraint == 'rel_festandsforfe') {
                $fe = new FrameElement($idFrameElement);
                $feMetonym = new FrameElement($this->data->idFEMetonymConstraint);
                Base::createEntityRelation($fe->idEntity, $this->data->constraint, $feMetonym->idEntity);
            } else if ($this->data->constraint == 'rel_festandsforlu') {
                $fe = new FrameElement($idFrameElement);
                $luMetonym = new LU($this->data->idLUMetonymConstraint);
                Base::createEntityRelation($fe->idEntity, $this->data->constraint, $luMetonym->idEntity);
            }
            $this->trigger('reload-gridConstraintFE');
            return $this->renderNotify("success", "Constraint created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/fes/constraints/{idEntityRelation}')]
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

    #[Get(path: '/fes/{idFrameElement}/semanticTypes')]
    public function semanticTypes(string $idFrameElement)
    {
        $this->data->idFrameElement = $idFrameElement;
        $this->data->frameElement = new FrameElement($idFrameElement);
        return $this->render("semanticTypes");
    }

    #[Get(path: '/fes/{idFrameElement}/semanticTypes/formAdd')]
    public function semanticTypesAdd(string $idFrameElement)
    {
        $this->data->idFrameElement = $idFrameElement;
        return $this->render("Structure.FE.SemanticType.formAdd");
    }

    #[Get(path: '/fes/{idFrameElement}/semanticTypes/grid')]
    public function semanticTypesGrid(string $idFrameElement)
    {
        $this->data->idFrameElement = $idFrameElement;
        $this->data->relations = FrameService::listFESemanticTypes($idFrameElement);
        return $this->render("Structure.FE.SemanticType.grid");
    }

    #[Post(path: '/fes/{idFrameElement}/semanticTypes')]
    public function addSemanticType(int $idFrameElement)
    {
        try {
            $this->data->new->idFrameElement = $idFrameElement;
            FrameService::addFESemanticType($this->data->new);
            $this->trigger('reload-gridSTFERelation');
            return $this->renderNotify("success", "Semantic Type added.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/fes/semanticTypes/{idEntityRelation}')]
    public function deleteSemanticType(int $idEntityRelation)
    {
        try {
            FrameService::deleteRelation($idEntityRelation);
            $this->trigger('reload-gridSTFERelation');
            return $this->renderNotify("success", "Semantic Type deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }
}
