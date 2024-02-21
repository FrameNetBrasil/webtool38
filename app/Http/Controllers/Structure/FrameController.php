<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Repositories\Domain;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\ViewFrame;
use App\Repositories\ViewFrameElement;
use App\Repositories\ViewLU;
use App\Services\AppService;
use App\Services\EntryService;
use App\Services\FrameService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;
use Illuminate\Support\Facades\Request;
use Orkester\Manager;

#[Middleware("master")]
class FrameController extends Controller
{
    #[Get(path: '/frame')]
    public function browse()
    {
        $this->data->search ??= session('searchFrame') ?? (object)[
            'frame' => '',
            'fe' => '',
            'lu' => '',
            'listBy' => '',
        ];
        $this->data->search->_token = csrf_token();
        debug($this->data);
        return $this->render('browse');
    }

    #[Post(path: '/frame/grid')]
    public function grid()
    {
        $search = (object)[
            'frame' => $this->data->search->frame ?? '',
            'fe' => $this->data->search->fe ?? '',
            'lu' => $this->data->search->lu ?? '',
            'listBy' => $this->data->search->listBy ?? '',
        ];
        session(['searchFrame' => $search]);
        $this->data->search->_token = csrf_token();
        return $this->render("grid");
    }

    #[Get(path: '/frame/new')]
    public function new()
    {
        return $this->render("new");
    }

    #[Post(path: '/frame')]
    public function newFrame()
    {
        try {
            $frame = new Frame();
            $frame->create($this->data->new);
            $this->data->frame = $frame;
            return $this->clientRedirect("/frame/{$frame->idFrame}/edit");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/frame/listForSelect')]
    public function listForSelect()
    {
        $data = Manager::getData();
        $q = $data->q ?? '';
        $frame = new Frame();
        return $frame->listForSelect($q)->getResult();
    }

    #[Post(path: '/frame/listForTree')]
    public function listForTree()
    {
        $data = Manager::getData();
        debug($data);
        $result = [];
        $id = $data->id ?? '';
        if ($id != '') {
            $idFrame = substr($id, 1);
            $resultFE = FEController::listForTreeByFrame($idFrame);
            $resultLU = LUController::listForTreeByFrame($idFrame);
            return array_merge($resultFE, $resultLU);
        } else {
            $filter = $data;
            if (!(($filter->fe ?? false) || ($filter->lu ?? false))) {
                $frame = new ViewFrame();
                $frames = $frame->listByFilter($filter)->asQuery()->getResult();
                foreach ($frames as $row) {
                    $node = [];
                    $node['id'] = 'f' . $row['idFrame'];
                    $node['type'] = 'frame';
                    $node['name'] = [$row['name'], $row['description']];
                    $node['state'] = 'closed';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-frame';
                    $node['children'] = [];
                    $result[] = $node;
                }
                $icon = 'material-icons-outlined wt-tree-icon wt-icon-frame';
            } else {
                if ($filter->fe ?? false) {
                    $result = FEController::listForTreeByName($filter->fe);
                    $icon = "material-icons wt-tree-icon wt-icon-fe-core";
                } else if ($filter->lu ?? false) {
                    $result = LUController::listForTreeByName($filter->lu);
                    $icon = 'material-icons-outlined wt-tree-icon wt-icon-lu';
                }
            }
            $total = count($result);
            return [
                'total' => $total,
                'rows' => $result,
                'footer' => [
                    [
                        'type' => 'frame',
                        'name' => ["{$total} record(s)", ''],
                        'iconCls' => $icon
                    ]
                ]
            ];
        }
    }

    #[Get(path: '/frame/{id}')]
    #[Get(path: '/frame/{id}/main')]
    public function edit(string $id)
    {
        $this->data->frame = new Frame($id);
        $this->data->classification = FrameService::getClassification($this->data->frame);
        return $this->render("edit");
    }

    #[Get(path: '/frame/{id}/entries')]
    public function formEntries(string $id)
    {
        $this->data->frame = new Frame($id);
        $entry = new Entry();
        $this->data->entries = $entry->listByIdEntity($this->data->frame->idEntity);
        $this->data->languages = AppService::availableLanguages();
        return $this->render("Structure.Entry.main");
    }

    #[Get(path: '/frame/{id}/fes')]
    public function fes(string $id)
    {
        $this->data->idFrame = $id;
        return $this->render("Structure.FE.child");
    }

    #[Get(path: '/frame/{id}/fes/formNew')]
    public function formNewFE(string $id)
    {
        $this->data->idFrame = $id;
        return $this->render("Structure.FE.formNew");
    }

    #[Get(path: '/frame/{id}/fes/grid')]
    public function gridFE(string $id)
    {
        $this->data->idFrame = $id;
        $this->data->fes = FrameService::listFEForGrid($id);
        return $this->render("Structure.FE.grid");
    }

    #[Get(path: '/frame/{id}/lus')]
    public function lus(string $id)
    {
        $this->data->frame = new Frame($id);
        return $this->render("Structure.LU.child");
    }

    #[Get(path: '/frame/{id}/lus/formNew')]
    public function formNewLU(string $id)
    {
        $this->data->idFrame = $id;
        return $this->render("Structure.LU.formNew");
    }

    #[Get(path: '/frame/{id}/lus/grid')]
    public function gridLU(string $id)
    {
        $this->data->idFrame = $id;
        $frame = new Frame($id);
        $this->data->lus = $frame->listLU()->asQuery()->getResult();
        return $this->render("Structure.LU.grid");
    }

    #[Get(path: '/frame/{id}/classification')]
    public function classification(string $id)
    {
    }

    #[Get(path: '/frame/{id}/relations')]
    public function relations(string $id)
    {
        $this->data->idFrame = $id;
        $this->data->frame = new Frame($id);
        return $this->render("Structure.Relation.frameChild");
    }

    #[Get(path: '/frame/{id}/relations/formNew')]
    public function formNewRelation(string $id)
    {
        $this->data->idFrame = $id;
        return $this->render("Structure.Relation.frameFormNew");
    }


    #[Get(path: '/frame/{id}/relations/grid')]
    public function gridRelation(string $id)
    {
        $this->data->idFrame = $id;
        $this->data->relations = FrameService::listRelations($id);
        return $this->render("Structure.Relation.frameGrid");
    }

    #[Post(path: '/frame/{id}/relations')]
    public function newRelation(int $id)
    {
        try {
            $this->data->new->idFrame = $id;
            FrameService::newRelation($this->data->new);
            $this->trigger('reload-gridRelation');
            return $this->renderNotify("success", "Relation created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/frame/relations/{idEntityRelation}')]
    public function deleteRelation(int $idEntityRelation)
    {
        try {
            $relation = new EntityRelation($idEntityRelation);
            $relation->delete();
            $this->trigger('reload-gridRelation');
            return $this->renderNotify("success", "Relation deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/frame/{id}/fes/relations')]
    public function fesRelations(string $id)
    {
        $this->data->idFrame = $id;
        return $this->render("fesRelations");
    }

    #[Get(path: '/frame/{id}/fes/relations/formNew')]
    public function fesRelationsFormNew(string $id)
    {
        $this->data->idFrame = $id;
        return $this->render("Structure.Frame.FERelation.formNew");
    }

    #[Get(path: '/frame/{id}/fes/relations/grid')]
    public function fesRelationsGrid(string $id)
    {
        $this->data->idFrame = $id;
        $this->data->relations = FrameService::listInternalRelationsFE($id);
        return $this->render("Structure.Frame.FERelation.grid");
    }

    #[Post(path: '/frame/{id}/fes/relations')]
    public function feRelationsNew(string $id)
    {
        try {
            FrameService::newInternalRelationFE($this->data);
            $this->trigger('reload-gridFEInternalRelation');
            return $this->renderNotify("success", "Relation created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/frame/fes/relations/{idEntityRelation}')]
    public function fesRelationDelete(int $idEntityRelation)
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

    #[Get(path: '/frame/{id}/semanticTypes')]
    public function semanticTypes(string $id)
    {
        $frame = new Frame($id);
        $this->data->idEntity = $frame->idEntity;
        $this->data->root = "@framal_type";
        return $this->render("Structure.SemanticType.child");
    }

}
