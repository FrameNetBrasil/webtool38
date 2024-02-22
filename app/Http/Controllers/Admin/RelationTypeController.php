<?php

namespace App\Http\Controllers\Admin;

use App\Data\CreateRelationTypeData;
use App\Data\SearchRelationGroupData;
use App\Http\Controllers\Controller;
use App\Repositories\Entry;
use App\Repositories\RelationGroup;
use App\Repositories\RelationType;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'admin')]
class RelationTypeController extends Controller
{
    public static function listForTreeByRelationGroup(int $idRelationGroup)
    {
        $result = [];
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
    }

    public static function listForTreeByName(string $name)
    {
        $result = [];
        $filter = (object)[
            'name' => $name
        ];
        $rt = new RelationType();
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
        return $result;
    }

    #[Get(path: '/relationtype')]
    public function browse()
    {
        data('search', session('searchRG') ?? SearchRelationGroupData::from());
        return $this->render('Admin.RelationGroup.browse');
    }

    #[Get(path: '/relationtype/new')]
    public function new()
    {
        $this->data->_layout = 'main';
        return $this->render("new");
    }

    #[Post(path: '/relationtype')]
    public function newRelationType()
    {
        try {
            $relationType = new RelationType();
            $data = CreateRelationTypeData::validateAndCreate((array)data('new'));
            $relationType->create($data);
            $this->trigger('reload-gridRT');
            return $this->renderNotify("success", "RelationType created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/relationtype/new')]
    public function newRelationTypeMain()
    {
        try {
            $relationType = new RelationType();
            $data = CreateRelationTypeData::validateAndCreate((array)data('new'));
            $relationType->create($data);
            $this->clientRedirect("/relationtype/{$relationType->idRelationType}");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/relationtype/grid')]
    public function grid()
    {
        data('search', SearchRelationGroupData::from(data('search')));
        session(['searchRG' => data('search')]);
        return $this->render('Admin.RelationGroup.grid');
    }

    #[Get(path: '/relationtype/{id}/edit')]
    public function edit(string $id)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $this->data->relationType = new RelationType($id);
        $this->data->relationType->retrieveAssociation("relationGroup", $idLanguage);
        return $this->render("edit");
    }

    #[Get(path: '/relationtype/{id}/main')]
    public function main(string $id)
    {
        $this->data->_layout = 'main';
        return $this->edit($id);
    }

    #[Get(path: '/relationtype/{id}/entries')]
    public function formEntries(string $id)
    {
        $this->data->relationType = new RelationType($id);
        $entry = new Entry();
        $this->data->entries = $entry->listByIdEntity($this->data->relationType->idEntity);
        $this->data->languages = AppService::availableLanguages();
        return $this->render("Structure.Entry.main");
    }

    #[Get(path: '/relationtype/{id}/formEdit')]
    public function formEdit(string $id)
    {
        $this->data->relationType = new RelationType($id);
        return $this->render("formEdit");
    }

    #[Put(path: '/relationtype/{id}')]
    public function update(string $id)
    {
        try {
            $relationType = new RelationType($id);
            $data = CreateRelationTypeData::validateAndCreate((array)data('update'));
            $relationType->update($data);
            $this->trigger('reload-gridRT');
            return $this->renderNotify("success", "RelationType updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/relationtype/{id}')]
    public function delete(string $id)
    {
        try {
            $relationType = new RelationType($id);
            $relationType->delete();
            $this->trigger('reload-gridRT');
            return $this->renderNotify("success", "RelationType deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/relationtype/{id}/main')]
    public function deleteFromMain(string $id)
    {
        try {
            $relationType = new RelationType($id);
            $relationType->delete();
            return $this->clientRedirect("/relationgroup");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

}
