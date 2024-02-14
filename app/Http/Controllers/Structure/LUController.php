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
use App\Services\LUService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'auth')]
class LUController extends Controller
{

    #[Post(path: '/lus')]
    public function newLU()
    {
        try {
            $lu = new LU();
            $lu->create($this->data->new);
            $this->trigger('reload-gridLU');
            return $this->renderNotify("success", "LU created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/lus/listForSelect')]
    public function listForSelect()
    {
        return LUService::listForSelect();
    }

    #[Get(path: '/lus/listForEvent')]
    public function listForEvent()
    {
        return LUService::listForEvent();
    }
    #[Get(path: '/lus/{idLU}/edit')]
    public function edit(string $idLU)
    {
        $this->data->lu = new LU($idLU);
        return $this->render("pageEdit");
    }

    #[Delete(path: '/lus/{idLU}')]
    public function delete(string $idLU)
    {
        try {
            $fe = new LU($idLU);
            $fe->delete();
            $this->trigger('reload-gridLU');
            return $this->renderNotify("success", "LU deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/lus/{idLU}/formEdit')]
    public function formEdit(string $idLU)
    {
        $this->data->lu = new LU($idLU);
        return $this->render("formEdit");
    }

    #[Put(path: '/lus/{idLU}')]
    public function update(string $idLU)
    {
        $lu = new LU($idLU);
        $lu->update($this->data->update);
        $this->trigger('reload-gridLU');
        return $this->renderNotify("success", "LU updated.");
    }

    #[Get(path: '/lus/{idLU}/constraints')]
    public function constraints(string $idLU)
    {
        $this->data->idLU = $idLU;
        return $this->render("constraints");
    }

    #[Get(path: '/lus/{idLU}/constraints/formNew/{fragment?}')]
    public function constraintsFormNew(int $idLU, ?string $fragment = null)
    {
        $this->data->idLU = $idLU;
        $this->data->lu = new LU($idLU);
        $this->data->fragment = $fragment;
        return $this->render("Structure.LU.Constraint.formNew", $fragment);
    }

    #[Get(path: '/lus/{idLU}/constraints/grid')]
    public function constraintsGrid(int $idLU)
    {
        debug($this->data);
        $this->data->idLU = $idLU;
        $lu = new LU($idLU);
        $constraint = new ViewConstraint();
        $this->data->constraints = $constraint->listByIdConstrained($lu->idEntity);
        return $this->render("Structure.LU.Constraint.grid");
    }

    #[Post(path: '/lus/{idLU}/constraints')]
    public function constraintsNew($idLU)
    {
        try {
            debug($this->data);
            $this->data->idLU = $idLU;
            if ($this->data->constraint == 'rel_lustandsforlu') {
                $lu = new LU($idLU);
                $luMetonym = new LU($this->data->idLUMetonymConstraint);
                Base::createEntityRelation($lu->idEntity, $this->data->constraint, $luMetonym->idEntity);
            } else if ($this->data->constraint == 'rel_luequivalence') {
                $lu = new LU($idLU);
                $luEquivalence = new LU($this->data->idLUEquivalenceConstraint);
                Base::createEntityRelation($lu->idEntity, $this->data->constraint, $luEquivalence->idEntity);
            }
            $this->trigger('reload-gridConstraintLU');
            return $this->renderNotify("success", "Constraint created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/lus/constraints/{idEntityRelation}')]
    public function deleteConstraint(int $idEntityRelation)
    {
        try {
            FrameService::deleteRelation($idEntityRelation);
            $this->trigger('reload-gridConstraintLU');
            return $this->renderNotify("success", "Constraint deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/lus/{idLU}/semanticTypes')]
    public function semanticTypes(string $idLU)
    {
        $this->data->idLU = $idLU;
        $this->data->lu = new LU($idLU);
        return $this->render("semanticTypes");
    }

    #[Get(path: '/lus/{idLU}/semanticTypes/formAdd')]
    public function semanticTypesAdd(string $idLU)
    {
        $this->data->idLU = $idLU;
        return $this->render("Structure.LU.SemanticType.formAdd");
    }

    #[Get(path: '/lus/{idLU}/semanticTypes/grid')]
    public function semanticTypesGrid(string $idLU)
    {
        $this->data->idLU = $idLU;
        $this->data->relations = LUService::listSemanticTypes($idLU);
        return $this->render("Structure.LU.SemanticType.grid");
    }

    #[Post(path: '/lus/{idLU}/semanticTypes')]
    public function addSemanticType(int $idLU)
    {
        try {
            $this->data->new->idLU = $idLU;
            LUService::addSemanticType($this->data->new);
            $this->trigger('reload-gridSTLURelation');
            return $this->renderNotify("success", "Semantic Type added.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/lus/relations/{idEntityRelation}')]
    public function deleteSemanticType(int $idEntityRelation)
    {
        try {
            LUService::deleteRelation($idEntityRelation);
            $this->trigger('reload-gridSTLURelation');
            return $this->renderNotify("success", "Semantic Type deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

}
