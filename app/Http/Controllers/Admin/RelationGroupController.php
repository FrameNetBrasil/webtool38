<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Corpus;
use App\Repositories\Domain;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\RelationGroup;
use App\Repositories\RelationType;
use App\Services\AppService;
use App\Services\CorpusService;
use App\Services\FrameService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;
use Illuminate\Support\Facades\Request;
use Orkester\Manager;

#[Middleware(name: 'auth')]
class RelationGroupController extends Controller
{
    #[Get(path: '/relationgroup')]
    public function browse()
    {
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        $this->data->_action = 'browse';
        return $this->render('main');
    }

    #[Get(path: '/relationgroup/new')]
    public function new()
    {
        $this->data->_action = 'new';
        return $this->render("main");
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
        $this->data->search->_token = csrf_token();
        $response = $this->render("grid");
        $query = [
            'search_relationGroup' => $this->data->search->relationGroup,
            'search_relationType' => $this->data->search->relationType,
        ];
        $response->header('HX-Replace-Url', '/relationgroup?' . http_build_query($query));
        return $response;
    }

    #[Post(path: '/relationgroup/listForTree')]
    public function listForTree()
    {
        $data = Manager::getData();
        debug($data);
        $result = [];
        $id = $data->id ?? '';
        if ($id != '') {
            $idRelationGroup = substr($id, 1);
            $rg = new RelationGroup($idRelationGroup);
            $rts = $rg->listRelationType()->getResult();
            foreach ($rts as $row) {
                $node = [];
                $node['id'] = 't' . $row['idRelationType'];
                $node['type'] = 'relationType';
                $node['name'] = [$row['name'], $row['description']];
                $node['state'] = 'closed';
                $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-detail';
                $node['children'] = [];
                $result[] = $node;
            }
            return $result;
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
            } else {
                $rt = new RelationType();
                $filter->name = $filter->relationType;
                $rts = $rt->listByFilter($filter)->getResult();
                foreach ($rts as $row) {
                    $node = [];
                    $node['id'] = 't' . $row['idRelationType'];
                    $node['type'] = 'relationType';
                    $node['name'] = [$row['name'], $row['description']];
                    $node['state'] = 'closed';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-detail';
                    $node['children'] = [];
                    $result[] = $node;
                }
            }
            $total = count($result);
            return [
                'total' => $total,
                'rows' => $result,
                'footer' => [
                    [
                        'type' => 'relationGroup',
                        'name' => ["{$total} record(s)", ''],
                        'iconCls' => 'material-icons-outlined wt-tree-icon wt-icon-master'
                    ]
                ]
            ];
        }
    }

    #[Get(path: '/relationgroup/{id}/edit')]
    public function edit(string $id)
    {
        $this->data->relationGroup = new RelationGroup($id);
        $this->data->_action = 'edit';
        return $this->render("main");
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
        return $this->render("Admin.RelationGroup.RelationType.child");
    }

    #[Get(path: '/relationgroup/{id}/rts/formNew')]
    public function formNewRT(string $id)
    {
        $this->data->idRelationGroup = $id;
        return $this->render("Admin.RelationGroup.RelationType.formNew");
    }

    #[Get(path: '/relationgroup/{id}/rts/grid')]
    public function gridRT(string $id)
    {
        $this->data->idRelationGroup = $id;
        $relationGroup = new RelationGroup($id);
        $this->data->rts = $relationGroup->listRelationType()->getResult();
        return $this->render("Admin.RelationGroup.RelationType.grid");
    }
}
