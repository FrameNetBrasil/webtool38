<?php

namespace App\Http\Controllers\Structure;

use App\Data\CreateFrameData;
use App\Data\CreateRelationFEInternalData;
use App\Data\SearchFrameData;
use App\Data\UpdateFrameClassificationData;
use App\Http\Controllers\Controller;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\ViewFrame;
use App\Services\AppService;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class FrameController extends Controller
{
    #[Get(path: '/frame')]
    public function browse()
    {
        data('search', session('searchFrame') ?? SearchFrameData::from());
        return $this->render('browse');
    }

    #[Post(path: '/frame/grid')]
    public function grid()
    {
        data('search', SearchFrameData::from(data('search')));
        session(['searchFrame' => $this->data->search]);
        return $this->render("grid");
    }

    #[Get(path: '/frame/new')]
    public function new()
    {
        return $this->render("new");
    }

    #[Post(path: '/frame')]
    public function postFrame()
    {
        try {
            $frame = new Frame();
            $frame->create(CreateFrameData::from(data('new')));
            data('frame', $frame);
            return $this->clientRedirect("/frame/{$frame->idFrame}");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/frame/{id}')]
    public function delete(string $id)
    {
        try {
            $frame = new Frame($id);
            $frame->delete();
            return $this->clientRedirect("/frame");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/frame/listForSelect')]
    public function listForSelect()
    {
        $frame = new Frame();
        return $frame->listForSelect(data('q'))->getResult();
    }

    #[Post(path: '/frame/listForTree')]
    public function listForTree()
    {
        $search = SearchFrameData::from($this->data);
        $result = [];
        $id = data('id', default:'');
        if ($id != '') {
            $idFrame = substr($id, 1);
            $resultFE = FEController::listForTreeByFrame($idFrame);
            $resultLU = LUController::listForTreeByFrame($idFrame);
            return array_merge($resultFE, $resultLU);
        } else {
            $icon = 'material-icons-outlined wt-tree-icon wt-icon-frame';
            if (($search->fe == '') && ($search->lu == '')) {
                $frame = new ViewFrame();
                $frames = $frame->listByFilter($search)->getResult();
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
                if ($search->fe != '') {
                    $result = FEController::listForTreeByName($search->fe);
                    $icon = "material-icons wt-tree-icon wt-icon-fe-core";
                } else if ($search->lu != '') {
                    $result = LUController::listForTreeByName($search->lu);
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
        $frame = Frame::getById($id);
        data('frame', $frame);
        data('classification', Frame::getClassificationLabels($id));
        return $this->render("edit");
    }

    #[Get(path: '/frame/{id}/entries')]
    public function entries(string $id)
    {
        $frame = Frame::getById($id);
        data('frame', $frame);
        data('entries', Entry::listByIdEntity($frame->idEntity));
        data('languages', AppService::availableLanguages());
        return $this->render("Structure.Entry.main");
    }

    #[Get(path: '/frame/{id}/fes')]
    public function fes(string $id)
    {
        data('idFrame', $id);
        return $this->render("Structure.FE.child");
    }

    #[Get(path: '/frame/{id}/fes/formNew')]
    public function formNewFE(string $id)
    {
        data('idFrame', $id);
        return $this->render("Structure.FE.formNew");
    }

    #[Get(path: '/frame/{id}/fes/grid')]
    public function gridFE(string $id)
    {
        data('idFrame', $id);
        data('fes', FEController::listForTreeByFrame($id));
        return $this->render("Structure.FE.grid");
    }

    #[Get(path: '/frame/{id}/lus')]
    public function lus(string $id)
    {
        data('idFrame', $id);
        return $this->render("Structure.LU.child");
    }

    #[Get(path: '/frame/{id}/lus/formNew')]
    public function formNewLU(string $id)
    {
        data('idFrame', $id);
        return $this->render("Structure.LU.formNew");
    }

    #[Get(path: '/frame/{id}/lus/grid')]
    public function gridLU(string $id)
    {
        $frame = new Frame($id);
        data('idFrame', $id);
        data('lus', $frame->listLU()->asQuery()->getResult());
        return $this->render("Structure.LU.grid");
    }

    #[Get(path: '/frame/{id}/classification')]
    public function classification(string $id)
    {
        $frame = new Frame($id);
        data('idFrame', $id);
        data('frame', $frame);
        return $this->render("Structure.Classification.child");
    }

    #[Get(path: '/frame/{id}/classification/formFramalType')]
    public function formFramalType(string $id)
    {
        data('idFrame', $id);
        return $this->render("Structure.Classification.formFramalType");
    }

    #[Get(path: '/frame/{id}/classification/formFramalDomain')]
    public function formFramalDomain(string $id)
    {
        data('idFrame', $id);
        return $this->render("Structure.Classification.formFramalDomain");
    }

    #[Post(path: '/frame/{id}/classification/domain')]
    public function framalDomain(string $id)
    {
        try {
            $data = UpdateFrameClassificationData::validateAndCreate([
                'framalData' => (array)data('framalDomain')
            ]);
            $frame = new Frame($id);
            RelationService::updateFramalDomain($frame, $data);
            return $this->renderNotify("success", "Domain updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/frame/{id}/classification/type')]
    public function framalType(string $id)
    {
        try {
            $data = UpdateFrameClassificationData::validateAndCreate([
                'framalData' => (array)data('framalType')
            ]);
            $frame = new Frame($id);
            RelationService::updateFramalType($frame, $data);
            return $this->renderNotify("success", "Domain updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/frame/{id}/relations')]
    public function relations(string $id)
    {
        $frame = new Frame($id);
        data('idFrame', $id);
        data('frame', $frame);
        return $this->render("Structure.Relation.frameChild");
    }

    #[Get(path: '/frame/{id}/relations/formNew')]
    public function formNewRelation(string $id)
    {
        data('idFrame', $id);
        return $this->render("Structure.Relation.frameFormNew");
    }

    #[Get(path: '/frame/{id}/relations/grid')]
    public function gridRelation(string $id)
    {
        data('idFrame', $id);
        data('relations', RelationService::listRelationsFrame($id));
        return $this->render("Structure.Relation.frameGrid");
    }

    #[Get(path: '/frame/{id}/feRelations')]
    public function feRelations(string $id)
    {
        data('idFrame', $id);
        return $this->render("Structure.Relation.feInternalChild");
    }

    #[Get(path: '/frame/{id}/feRelations/formNew')]
    public function formNewFERelations(string $id)
    {
        data('idFrame', $id);
        return $this->render("Structure.Relation.feInternalFormNew");
    }

    #[Get(path: '/frame/{id}/feRelations/grid')]
    public function gridFERelations(string $id)
    {
        data('idFrame', $id);
        data('relations', RelationService::listRelationsFEInternal($id));
        return $this->render("Structure.Relation.feInternalGrid");
    }

    #[Post(path: '/frame/{id}/feRelations')]
    public function feRelationsNew(string $id)
    {
        try {
            $data = CreateRelationFEInternalData::validateAndCreate((array)data());
            debug($data);
//            $relationType->create($data);
//            $data = CreateRelationFEInternalData::from(data());
            RelationService::createRelationFEInternal($data);
            $this->trigger('reload-gridFEInternalRelation');
            return $this->renderNotify("success", "Relation created.");
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
