<?php

namespace App\Http\Controllers\Structure;

use App\Data\CreateFEData;
use App\Http\Controllers\Controller;
use App\Repositories\Base;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\LU;
use App\Repositories\Qualia;
use App\Repositories\ViewConstraint;
use App\Repositories\ViewFrameElement;
use App\Services\AppService;
use App\Services\EntryService;
use App\Services\FrameService;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'auth')]
class FEController extends Controller
{
    public static function listForTreeByFrame(int $idFrame)
    {
        $result = [];
        $icon = config('webtool.fe.icon.tree');
        $coreness = config('webtool.fe.coreness');
        $frame = new Frame($idFrame);
        $fes = $frame->listFE()->asQuery()->getResult();
        $orderedFe = [];
        foreach ($icon as $i => $j) {
            foreach ($fes as $fe) {
                if ($fe['coreType'] == $i) {
                    $orderedFe[] = $fe;
                }
            }
        }
        foreach ($orderedFe as $fe) {
            $node = [];
            $node['id'] = 'e' . $fe['idFrameElement'];
            $node['idFrameElement'] = $fe['idFrameElement'];
            $node['type'] = 'fe';
            $node['name'] = [$fe['name'], $fe['description']];
            $node['idColor'] = $fe['idColor'];
            $node['state'] = 'open';
            $node['iconCls'] = $icon[$fe['coreType']];
            $node['coreness'] = $coreness[$fe['coreType']];
            $node['children'] = null;
            $result[] = $node;
        }
        return $result;
    }

    public static function listForTreeByName(string $name)
    {
        $result = [];
        $filter = (object)[
            'fe' => $name
        ];
        $icon = config('webtool.fe.icon.tree');
        $fe = new ViewFrameElement();
        $fes = $fe->listByFilter($filter)->asQuery()->getResult();
        foreach ($fes as $row) {
            $node = [];
            $node['id'] = 'e' . $row['idFrameElement'];
            $node['type'] = 'feFrame';
            $node['name'] = [$row['name'], $row['description'], $row['frameName']];
            $node['idColor'] = $row['idColor'];
            $node['state'] = 'closed';
            $node['iconCls'] = $icon[$row['coreType']];
            $node['children'] = [];
            $result[] = $node;
        }
        return $result;
    }

    #[Post(path: '/fe')]
    public function newFE()
    {
        try {
            $fe = new FrameElement();
            $fe->create(CreateFEData::from(data('new')));
            $this->trigger('reload-gridFE');
            return $this->renderNotify("success", "FrameElement created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/fe/{id}/edit')]
    public function edit(string $id)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $this->data->frameElement = new FrameElement($id);
        $this->data->frameElement->retrieveAssociation("frame", $idLanguage);
        return $this->render("edit");
    }

    #[Get(path: '/fe/{id}/main')]
    public function main(string $id)
    {
        $this->data->_layout = 'main';
        return $this->edit($id);
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
        $this->data->idEntityRelation = $idEntityRelation;
        return $this->render("Structure.Relation.feChild");
    }

    #[Get(path: '/fe/relations/{idEntityRelation}/formNew')]
    public function relationsFEFormNew(int $idEntityRelation)
    {
        $relation = new EntityRelation($idEntityRelation);
        data('idEntityRelation', $idEntityRelation);
        $frame = new Frame();
        $frame->getByIdEntity($relation->idEntity1);
        data('frame', $frame);
        $relatedFrame = new Frame();
        $relatedFrame->getByIdEntity($relation->idEntity2);
        data('relatedFrame', $relatedFrame);
        $config = config('webtool.relations');
        data('relation', (object)[
            'name' => $config[$relation->entry]['direct'],
            'entry' => $relation->entry
        ]);
        return $this->render("Structure.Relation.feFormNew");
    }

    #[Get(path: '/fe/relations/{idEntityRelation}/grid')]
    public function gridRelationsFE(int $idEntityRelation)
    {
        $relation = new EntityRelation($idEntityRelation);
        data('idEntityRelation', $idEntityRelation);
        $frame = new Frame();
        $frame->getByIdEntity($relation->idEntity1);
        data('frame', $frame);
        $relatedFrame = new Frame();
        $relatedFrame->getByIdEntity($relation->idEntity2);
        data('relatedFrame', $relatedFrame);
        $config = config('webtool.relations');
        data('relation', (object)[
            'name' => $config[$relation->entry]['direct'],
            'entry' => $relation->entry
        ]);
        data('relations', RelationService::listRelationsFE($idEntityRelation));
        return $this->render("Structure.Relation.feGrid");
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
