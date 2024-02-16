<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Repositories\Domain;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Services\AppService;
use App\Services\EntryService;
use App\Services\FrameService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;
use Illuminate\Support\Facades\Request;

#[Middleware(name: 'auth')]
class FrameController extends Controller
{
    #[Get(path: '/frame')]
    public function browse()
    {
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        return $this->render('pageBrowse');
    }

    #[Get(path: '/frame/new')]
    public function new()
    {
        return $this->render("pageNew");
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
        $response = $this->render("slotGrid");
        $query = [
            'search_idDomain' => $this->data->search->idDomain,
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
        return FrameService::listForTree();
    }

    #[Get(path: '/frame/{id}/edit')]
    public function edit(string $id)
    {
        $this->data->frame = new Frame($id);
        $this->data->classification = FrameService::getClassification($this->data->frame);
        return $this->render("pageEdit");
    }

    #[Get(path: '/frame/{id}/entries')]
    public function formEntries(string $id)
    {
        $this->data->frame = new Frame($id);
        $entry = new Entry();
        $this->data->entries = $entry->listByIdEntity($this->data->frame->idEntity);
        $this->data->languages = AppService::availableLanguages();
        return $this->render("entries");
    }

    #[Put(path: '/frame/{id}/entries')]
    public function entries(int $id)
    {
        try {
            EntryService::updateEntries($this->data);
            return $this->renderNotify("success", "Translations recorded.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/frame/{id}/fes')]
    public function fes(string $id)
    {
        $this->data->idFrame = $id;
        return $this->render("fes");
    }

    #[Get(path: '/frame/{id}/fes/formNew')]
    public function formNewFE(string $id)
    {
        $this->data->idFrame = $id;
        return $this->render("Structure.Frame.FE.formNew");
    }

    #[Get(path: '/frame/{id}/fes/grid')]
    public function gridFE(string $id)
    {
        $this->data->idFrame = $id;
        $this->data->fes = FrameService::listFEForGrid($id);
        return $this->render("Structure.Frame.FE.grid");
    }

    #[Get(path: '/frame/{id}/lus')]
    public function lus(string $id)
    {
        $this->data->frame = new Frame($id);
        return $this->render("lus");
    }

    #[Get(path: '/frame/{id}/lus/formNew')]
    public function formNewLU(string $id)
    {
        $this->data->idFrame = $id;
        return $this->render("Structure.Frame.LU.formNew");
    }

    #[Get(path: '/frame/{id}/lus/grid')]
    public function gridLU(string $id)
    {
        $this->data->idFrame = $id;
        $this->data->lus = FrameService::listLUForGrid($id);
        return $this->render("Structure.Frame.LU.grid");
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
            FrameService::deleteRelation($idEntityRelation);
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
            FrameService::deleteRelation($idEntityRelation);
            $this->trigger('reload-gridFEInternalRelation');
            return $this->renderNotify("success", "Relation deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/frame/{id}/semanticTypes')]
    public function semanticTypes(string $id)
    {
        $this->data->idFrame = $id;
        $this->data->frame = new Frame($id);
        return $this->render("semanticTypes");
    }

    #[Get(path: '/frame/{id}/semanticTypes/formAdd')]
    public function semanticTypesAdd(string $id)
    {
        $this->data->idFrame = $id;
        return $this->render("Structure.Frame.SemanticType.formAdd");
    }

    #[Get(path: '/frame/{id}/semanticTypes/grid')]
    public function semanticTypesGrid(string $id)
    {
        $this->data->idFrame = $id;
        $this->data->relations = FrameService::listSemanticTypes($id);
        return $this->render("Structure.Frame.SemanticType.grid");
    }

    #[Post(path: '/frame/{id}/semanticTypes')]
    public function addSemanticType(int $id)
    {
        try {
            $this->data->new->idFrame = $id;
            $this->trigger('reload-gridSTRelation');
            return $this->renderNotify("success", "Semantic Type added.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/frame/semanticTypes/{idEntityRelation}')]
    public function deleteSemanticType(int $idEntityRelation)
    {
        try {
            FrameService::deleteRelation($idEntityRelation);
            $this->trigger('reload-gridSTRelation');
            return $this->renderNotify("success", "Semantic Type deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }
}
