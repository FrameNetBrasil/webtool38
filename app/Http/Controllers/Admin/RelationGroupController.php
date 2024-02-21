<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Entry;
use App\Repositories\RelationGroup;
use App\Repositories\RelationType;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Orkester\Manager;

#[Middleware(name: 'admin')]
class RelationGroupController extends Controller
{
    #[Get(path: '/relationgroup')]
    public function browse()
    {
        $this->data->search ??= session('searchRG') ?? (object)[
            'relationGroup' => '',
            'relationType' => ''
        ];
        $this->data->search->_token = csrf_token();
        return $this->render('browse');
    }

    #[Get(path: '/relationgroup/new')]
    public function new()
    {
        return $this->render("new");
    }

    #[Post(path: '/relationgroup')]
    public function newRelationGroup()
    {
        try {
            $relationGroup = new RelationGroup();
            $relationGroup->create($this->data->new);
            $this->data->relationGroup = $relationGroup;
            return $this->clientRedirect("/relationgroup/{$relationGroup->idRelationGroup}/edit");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/relationgroup/grid')]
    public function grid()
    {
        $search = (object)[
            'relationGroup' => $this->data->search->relationGroup ?? '',
            'relationType' => $this->data->search->relationType ?? '',
        ];
        session(['searchRG' => $search]);
        $this->data->search->_token = csrf_token();
        return $this->render("grid");
    }

    #[Get(path: '/relationgroup/listForSelect')]
    public function listForSelect()
    {
        $data = Manager::getData();
        $q = $data->q ?? '';
        $rg = new RelationGroup();
        return $rg->listForSelect($q)->getResult();
    }
    #[Post(path: '/relationgroup/listForTree')]
    public function listForTree()
    {
        $data = Manager::getData();
        $result = [];
        $id = $data->id ?? '';
        if ($id != '') {
            $idRelationGroup = substr($id, 1);
            return RelationTypeController::listForTreeByRelationGroup($idRelationGroup);
        } else {
            $filter = $data;
            if (!isset($filter->relationType)) {
                $rg = new RelationGroup();
                $filter->name = $filter->relationGroup ?? null;
                $rgs = $rg->listByFilter($filter)->getResult();
                foreach ($rgs as $row) {
                    $node = [];
                    $node['id'] = 'g' . $row['idRelationGroup'];
                    $node['type'] = 'relationGroup';
                    $node['name'] = [$row['name'], $row['description']];
                    $node['state'] = 'closed';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-master';
                    $node['children'] = [];
                    $result[] = $node;
                }
                $icon = 'material-icons-outlined wt-tree-icon wt-icon-master';
            } else {
                $result = RelationTypeController::listForTreeByName($filter->relationType);
                $icon = 'material-icons-outlined wt-tree-icon wt-icon-detail';
            }
            $total = count($result);
            return [
                'total' => $total,
                'rows' => $result,
                'footer' => [
                    [
                        'type' => 'relationGroup',
                        'name' => ["{$total} record(s)", ''],
                        'iconCls' => $icon
                    ]
                ]
            ];
        }
    }

    #[Get(path: '/relationgroup/{id}')]
    #[Get(path: '/relationgroup/{id}/main')]
    public function edit(string $id)
    {
        $this->data->relationGroup = new RelationGroup($id);
        return $this->render("edit");
    }

    #[Get(path: '/relationgroup/{id}/entries')]
    public function formEntries(string $id)
    {
        $this->data->relationGroup = new RelationGroup($id);
        $entry = new Entry();
        $this->data->entries = $entry->listByIdEntity($this->data->relationGroup->idEntity);
        $this->data->languages = AppService::availableLanguages();
        return $this->render("Structure.Entry.main");
    }

    #[Get(path: '/relationgroup/{id}/rts')]
    public function rts(string $id)
    {
        $this->data->idRelationGroup = $id;
        return $this->render("Admin.RelationType.child");
    }

    #[Get(path: '/relationgroup/{id}/rts/formNew')]
    public function formNewRT(string $id)
    {
        $this->data->idRelationGroup = $id;
        return $this->render("Admin.RelationType.formNew");
    }

    #[Get(path: '/relationgroup/{id}/rts/grid')]
    public function gridRT(string $id)
    {
        $this->data->idRelationGroup = $id;
        $relationGroup = new RelationGroup($id);
        $this->data->rts = $relationGroup->listRelationType()->getResult();
        return $this->render("Admin.RelationType.grid");
    }

    #[Delete(path: '/relationgroup/{id}')]
    public function delete(string $id)
    {
        try {
            $relationGroup = new RelationGroup($id);
            $relationGroup->delete();
            return $this->clientRedirect("/relationgroup");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }
}
