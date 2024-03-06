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
use App\Repositories\ViewLU;
use App\Services\AppService;
use App\Services\EntryService;
use App\Services\FrameService;
use App\Services\LUService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;
use Orkester\Manager;

#[Middleware(name: 'auth')]
class LUController extends Controller
{

    public static function listForTreeByFrame(int $idFrame)
    {
        $result = [];
        $idLanguage = AppService::getCurrentIdLanguage();
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
    }

    public static function listForTreeByName(string $name)
    {
        $result = [];
        $filter = (object)[
            'lu' => $name
        ];
        $lu = new ViewLU();
        $lus = $lu->listByFilter($filter)->asQuery()->getResult();
        foreach ($lus as $i => $row) {
            $node = [];
            $node['id'] = 'l' . $row['idLU'];
            $node['type'] = 'luFrame';
            $node['name'] = [$row['name'], $row['senseDescription'], $row['frameName']];
            $node['state'] = 'closed';
            $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-lu';
            $node['children'] = [];
            $result[] = $node;
        }
        return $result;
    }

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
        $data = Manager::getData();
        $q = $data->q ?? '';
        $pos = $data->pos ?? '';
        $lu = new LU();
        return $lu->listForSelect($q, $pos)->getResult();
    }

    #[Get(path: '/lu/listForEvent')]
    public function listForEvent()
    {
        $data = Manager::getData();
        $q = $data->q ?? '';
        $lu = new LU();
        return $lu->listForEvent($q);
    }

    #[Get(path: '/lu/{id}/edit')]
    public function edit(string $id)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $this->data->lu = new LU($id);
        $this->data->lu->retrieveAssociation("frame", $idLanguage);
        return $this->render("edit");
    }

    #[Get(path: '/lu/{id}/main')]
    public function main(string $id)
    {
        $this->data->_layout = 'main';
        return $this->edit($id);
    }

    #[Delete(path: '/lu/{id}')]
    public function delete(string $id)
    {
        try {
            $lu = new LU($id);
            $lu->delete();
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
        debug($this->data);
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
        data('idLU', $id);
        return $this->render("Structure.Constraint.luChild");
    }

    #[Get(path: '/lu/{id}/constraints/formNew/{fragment?}')]
    public function constraintsFormNew(int $id, ?string $fragment = null)
    {
        data('idLU', $id);
        data('lu', new LU($id));
        data('fragment', $fragment ?? '');
        return $this->render("Structure.Constraint.luFormNew", $fragment);
    }

    #[Get(path: '/lu/{id}/constraints/grid')]
    public function constraintsGrid(int $id)
    {
        data('idLU', $id);
        $lu = new LU($id);
        data('lu', $lu);
        $constraint = new ViewConstraint();
        data('constraints', $constraint->listByIdConstrained($lu->idEntity));
        return $this->render("Structure.Constraint.luGrid");
    }

    #[Get(path: '/lu/{id}/semanticTypes')]
    public function semanticTypes(string $id)
    {
        $lu = new LU($id);
        $this->data->idEntity = $lu->idEntity;
        $this->data->root = "@lexical_type";
        return $this->render("Structure.SemanticType.child");
    }

}
