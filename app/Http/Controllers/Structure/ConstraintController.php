<?php

namespace App\Http\Controllers\Structure;

use App\Data\CreateRelationFEData;
use App\Data\CreateRelationFrameData;
use App\Data\RelationData;
use App\Http\Controllers\Controller;
use App\Repositories\Base;
use App\Repositories\EntityRelation;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\LU;
use App\Repositories\Qualia;
use App\Services\ConstraintService;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'auth')]
class ConstraintController extends Controller
{
    #[Post(path: '/constraint/fe/{id}')]
    public function constraintFE($id)
    {
        try {
            data('idFrameElement', $id);
            $fe = new FrameElement($id);
            $constraintEntry = data('constraint');
            if ($constraintEntry == 'rel_constraint_frame') {
                $cn = Base::createEntity('CN', 'con');
                $frame = new Frame(data('idFrameConstraint'));
                ConstraintService::create($cn->idEntity, $constraintEntry, $fe->idEntity, $frame->idEntity);
            } else if ($constraintEntry == 'rel_qualia') {
                $feQualia = new FrameElement(data('idFEQualiaConstraint'));
                $qualia = new Qualia(data('idQualiaConstraint'));
                RelationService::create($constraintEntry, $fe->idEntity,$feQualia->idEntity, $qualia->idEntity);
            } else if ($constraintEntry == 'rel_festandsforfe') {
                $fe = new FrameElement($id);
                $feMetonym = new FrameElement(data('idFEMetonymConstraint'));
                RelationService::create($constraintEntry, $fe->idEntity,$feMetonym->idEntity);
            } else if ($constraintEntry == 'rel_festandsforlu') {
                $fe = new FrameElement($id);
                $luMetonym = new LU(data('idLUMetonymConstraint'));
                RelationService::create($constraintEntry, $fe->idEntity,$luMetonym->idEntity);
            }
            $this->trigger('reload-gridConstraintFE');
            return $this->renderNotify("success", "Constraint created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/constraint/fe/{idConstraintInstance}')]
    public function deleteConstraintFE(int $idConstraintInstance)
    {
        try {
            ConstraintService::delete($idConstraintInstance);
            $this->trigger('reload-gridConstraintFE');
            return $this->renderNotify("success", "Constraint deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/constraint/lu/{id}')]
    public function constraintLU($id)
    {
        try {
            data('idLU', $id);
            $lu = new LU($id);
            $constraintEntry = data('constraint');
            if ($constraintEntry == 'rel_lustandsforlu') {
                $luMetonym = new LU(data('idLUMetonymConstraint'));
                RelationService::create($constraintEntry, $lu->idEntity,$luMetonym->idEntity);
            } else if ($constraintEntry == 'rel_luequivalence') {
                $luEquivalence = new LU(data('idLUEquivalenceConstraint'));
                RelationService::create($constraintEntry, $lu->idEntity,$luEquivalence->idEntity);
            }
            $this->trigger('reload-gridConstraintLU');
            return $this->renderNotify("success", "Constraint created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/constraint/lu/{idConstraintInstance}')]
    public function deleteConstraintLU(int $idConstraintInstance)
    {
        try {
            ConstraintService::delete($idConstraintInstance);
            $this->trigger('reload-gridConstraintLU');
            return $this->renderNotify("success", "Constraint deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

}
