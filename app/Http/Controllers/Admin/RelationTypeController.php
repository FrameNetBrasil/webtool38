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
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        $this->data->_action = 'browse';
        return $this->render('main');
    }

    #[Get(path: '/relationtype/new')]
    public function new()
    {
        return $this->render("new");
    }

    #[Post(path: '/relationtype')]
    public function newRelationType()
    {
        try {
            $relationType = new RelationType();
            $relationType->create($this->data->new);
            $this->trigger('reload-gridRT');
            return $this->renderNotify("success", "RelationType created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/relationtype/grid')]
    public function grid()
    {
        $this->data->search->_token = csrf_token();
        $response = $this->render("grid");
        $query = [
            'search_relationGroup' => $this->data->search->relationGroup,
            'search_relationType' => $this->data->search->relationType,
        ];
        $response->header('HX-Replace-Url', '/relationtype?' . http_build_query($query));
        return $response;
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
        $relationType = new RelationType($id);
        $relationType->update($this->data->update);
        $this->trigger('reload-gridRT');
        return $this->renderNotify("success", "RelationType updated.");
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
