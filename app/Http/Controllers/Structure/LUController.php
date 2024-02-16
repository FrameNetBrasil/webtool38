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

    #[Post(path: '/lu')]
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

    #[Get(path: '/lu/listForSelect')]
    public function listForSelect()
    {
        return LUService::listForSelect();
    }

    #[Get(path: '/lu/listForEvent')]
    public function listForEvent()
    {
        return LUService::listForEvent();
    }
    #[Get(path: '/lu/{id}/edit')]
    public function edit(string $id)
    {
        $this->data->lu = new LU($id);
        return $this->render("pageEdit");
    }

    #[Delete(path: '/lu/{id}')]
    public function delete(string $id)
    {
        try {
            $fe = new LU($id);
            $fe->delete();
            $this->trigger('reload-gridLU');
            return $this->renderNotify("success", "LU deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/lu/{id}/formEdit')]
    public function formEdit(string $id)
    {
        $this->data->lu = new LU($id);
        return $this->render("formEdit");
    }

    #[Put(path: '/lu/{id}')]
    public function update(string $id)
    {
        $lu = new LU($id);
        $lu->update($this->data->update);
        $this->trigger('reload-gridLU');
        return $this->renderNotify("success", "LU updated.");
    }

    #[Get(path: '/lu/{id}/constraints')]
    public function constraints(string $id)
    {
        $this->data->idLU = $id;
        return $this->render("constraints");
    }

    #[Get(path: '/lu/{id}/constraints/formNew/{fragment?}')]
    public function constraintsFormNew(int $id, ?string $fragment = null)
    {
        $this->data->idLU = $id;
        $this->data->lu = new LU($id);
        $this->data->fragment = $fragment;
        return $this->render("Structure.LU.Constraint.formNew", $fragment);
    }

    #[Get(path: '/lu/{id}/constraints/grid')]
    public function constraintsGrid(int $id)
    {
        debug($this->data);
        $this->data->idLU = $id;
        $lu = new LU($id);
        $constraint = new ViewConstraint();
        $this->data->constraints = $constraint->listByIdConstrained($lu->idEntity);
        return $this->render("Structure.LU.Constraint.grid");
    }

    #[Post(path: '/lu/{id}/constraints')]
    public function constraintsNew($id)
    {
        try {
            debug($this->data);
            $this->data->idLU = $id;
            if ($this->data->constraint == 'rel_lustandsforlu') {
                $lu = new LU($id);
                $luMetonym = new LU($this->data->idLUMetonymConstraint);
                Base::createEntityRelation($lu->idEntity, $this->data->constraint, $luMetonym->idEntity);
            } else if ($this->data->constraint == 'rel_luequivalence') {
                $lu = new LU($id);
                $luEquivalence = new LU($this->data->idLUEquivalenceConstraint);
                Base::createEntityRelation($lu->idEntity, $this->data->constraint, $luEquivalence->idEntity);
            }
            $this->trigger('reload-gridConstraintLU');
            return $this->renderNotify("success", "Constraint created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/lu/constraints/{idEntityRelation}')]
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

    #[Get(path: '/lu/{id}/semanticTypes')]
    public function semanticTypes(string $id)
    {
        $this->data->idLU = $id;
        $this->data->lu = new LU($id);
        return $this->render("semanticTypes");
    }

    #[Get(path: '/lu/{id}/semanticTypes/formAdd')]
    public function semanticTypesAdd(string $id)
    {
        $this->data->idLU = $id;
        return $this->render("Structure.LU.SemanticType.formAdd");
    }

    #[Get(path: '/lu/{id}/semanticTypes/grid')]
    public function semanticTypesGrid(string $id)
    {
        $this->data->idLU = $id;
        $this->data->relations = LUService::listSemanticTypes($id);
        return $this->render("Structure.LU.SemanticType.grid");
    }

    #[Post(path: '/lu/{id}/semanticTypes')]
    public function addSemanticType(int $id)
    {
        try {
            $this->data->new->idLU = $id;
            LUService::addSemanticType($this->data->new);
            $this->trigger('reload-gridSTLURelation');
            return $this->renderNotify("success", "Semantic Type added.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/lu/relations/{idEntityRelation}')]
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
