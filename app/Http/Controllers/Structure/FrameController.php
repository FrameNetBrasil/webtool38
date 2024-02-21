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
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        return $this->render('browse');
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

    #[Post(path: '/frame/grid')]
    public function grid()
    {
        $this->data->search->_token = csrf_token();
        $response = $this->render("grid");
        $query = [
            'search_frame' => $this->data->search->frame,
            'search_fe' => $this->data->search->fe,
            'search_lu' => $this->data->search->lu,
            'search_listBy' => $this->data->search->listBy,
        ];
        $response->header('HX-Replace-Url', '/frame?' . http_build_query($query));
        return $response;
    }

    #[Get(path: '/frame/listForSelect')]
    public function listForSelect()
    {
        return FrameService::listForSelect();
    }

    #[Post(path: '/frame/listForTree')]
    public function listForTree()
    {
        $data = Manager::getData();
        debug($data);
        $result = [];
        $idLanguage = AppService::getCurrentIdLanguage();
        $id = $data->id ?? '';
        if ($id != '') {
            $idFrame = substr($id, 1);
            $icon = config('webtool.fe.icon.tree');
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
                $node['type'] = 'fe';
                $node['name'] = [$fe['name'], $fe['description']];
                $node['idColor'] = $fe['idColor'];
                $node['state'] = 'open';
                $node['iconCls'] = $icon[$fe['coreType']];
                $node['children'] = null;
                $result[] = $node;
            }
            $lu = new ViewLU();
            $lus = $lu->listByFrame($idFrame, $idLanguage)->asQuery()->getResult();
            foreach ($lus as $lu) {
                $node = [];
                $node['id'] = 'l' . $lu['idLU'];
                $node['type'] = 'lu';
                $node['name'] = [$lu['name'], $lu['senseDescription']];;
                $node['state'] = 'open';
                $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-lu';
                $node['children'] = null;
                $result[] = $node;
            }
            return $result;
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
            } else {
                if ($filter->fe ?? false) {
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
                } else if ($filter->lu ?? false) {
                    $lu = new ViewLU();
                    $lus = $lu->listByFilter($filter)->asQuery()->getResult();
                    foreach ($lus as $i => $row) {
                        if ($i == 0) {
                            debug($row);
                        }

                        $node = [];
                        $node['id'] = 'l' . $row['idLU'];
                        $node['type'] = 'luFrame';
                        $node['name'] = [$row['name'], $row['senseDescription'], $row['frameName']];
                        $node['state'] = 'closed';
                        $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-lu';
                        $node['children'] = [];
                        $result[] = $node;
                    }
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
                        'iconCls' => 'material-icons-outlined wt-tree-icon wt-icon-frame'
                    ]
                ]
            ];
        }
    }

    #[Get(path: '/frame/{id}')]
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
        return $this->render("relations");
    }

    #[Get(path: '/frame/{id}/relations/formNew')]
    public function formNewRelation(string $id)
    {
        $this->data->idFrame = $id;
        return $this->render("Structure.Frame.Relation.formNew");
    }


    #[Get(path: '/frame/{id}/relations/grid')]
    public function gridRelation(string $id)
    {
        $this->data->idFrame = $id;
        $this->data->relations = FrameService::listRelations($id);
        return $this->render("Structure.Frame.Relation.grid");
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
