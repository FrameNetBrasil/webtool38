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
class SemanticTypeController extends Controller
{
    #[Get(path: '/semantictype')]
    public function browse()
    {
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        return $this->render('pageBrowse');
    }

    #[Get(path: '/semantictype/new')]
    public function new()
    {
        return $this->render("pageNew");
    }

    #[Post(path: '/semantictype')]
    public function newFrame()
    {
        try {
            $frame = new Frame();
            $frame->create($this->data->new);
            $this->data->frame = $frame;
            return $this->clientRedirect("/semantictype/{$frame->idFrame}/edit");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/semantictype/grid')]
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
        $response->header('HX-Replace-Url', '/semantictype?' . http_build_query($query));
        return $response;
    }

    #[Get(path: '/semantictype/listForSelect')]
    public function listForSelect()
    {
        return FrameService::listForSelect();
    }

    #[Post(path: '/semantictype/listForTree')]
    public function listForTree()
    {
        return FrameService::listForTree();
    }

    #[Get(path: '/semantictype/{id}/edit')]
    public function edit(string $id)
    {
        $this->data->frame = new Frame($id);
        $this->data->classification = FrameService::getClassification($this->data->frame);
        return $this->render("pageEdit");
    }

    #[Get(path: '/semantictype/{idFrame}/entries')]
    public function formEntries(string $idFrame)
    {
        $this->data->frame = new Frame($idFrame);
        $entry = new Entry();
        $this->data->entries = $entry->listByIdEntity($this->data->frame->idEntity);
        $this->data->languages = AppService::availableLanguages();
        return $this->render("entries");
    }

    #[Put(path: '/semantictype/{idFrame}/entries')]
    public function entries(int $idFrame)
    {
        try {
            EntryService::updateEntries($this->data);
            return $this->renderNotify("success", "Translations recorded.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/semantictype/{idFrame}/fes')]
    public function fes(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        return $this->render("fes");
    }

    #[Get(path: '/semantictype/{idFrame}/fes/formNew')]
    public function formNewFE(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        return $this->render("Structure.Frame.FE.formNew");
    }

    #[Get(path: '/semantictype/{idFrame}/fes/grid')]
    public function gridFE(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->fes = FrameService::listFEForGrid($idFrame);
        return $this->render("Structure.Frame.FE.grid");
    }

    #[Get(path: '/semantictype/{idFrame}/lus')]
    public function lus(string $idFrame)
    {
        $this->data->frame = new Frame($idFrame);
        return $this->render("lus");
    }

    #[Get(path: '/semantictype/{idFrame}/lus/formNew')]
    public function formNewLU(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        return $this->render("Structure.Frame.LU.formNew");
    }

    #[Get(path: '/semantictype/{idFrame}/lus/grid')]
    public function gridLU(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->lus = FrameService::listLUForGrid($idFrame);
        return $this->render("Structure.Frame.LU.grid");
    }

    #[Get(path: '/semantictype/{id}/classification')]
    public function classification(string $id)
    {
    }

    #[Get(path: '/semantictype/{idFrame}/relations')]
    public function relations(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->frame = new Frame($idFrame);
        return $this->render("relations");
    }

    #[Get(path: '/semantictype/{idFrame}/relations/formNew')]
    public function formNewRelation(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        return $this->render("Structure.Frame.Relation.formNew");
    }


    #[Get(path: '/semantictype/{idFrame}/relations/grid')]
    public function gridRelation(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->relations = FrameService::listRelations($idFrame);
        return $this->render("Structure.Frame.Relation.grid");
    }

    #[Post(path: '/semantictype/{idFrame}/relations')]
    public function newRelation(int $idFrame)
    {
        try {
            $this->data->new->idFrame = $idFrame;
            FrameService::newRelation($this->data->new);
            $this->trigger('reload-gridRelation');
            return $this->renderNotify("success", "Relation created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/semantictype/relations/{idEntityRelation}')]
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

    #[Get(path: '/semantictype/{idFrame}/fes/relations')]
    public function fesRelations(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        return $this->render("fesRelations");
    }

    #[Get(path: '/semantictype/{idFrame}/fes/relations/formNew')]
    public function fesRelationsFormNew(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        return $this->render("Structure.Frame.FERelation.formNew");
    }

    #[Get(path: '/semantictype/{idFrame}/fes/relations/grid')]
    public function fesRelationsGrid(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->relations = FrameService::listInternalRelationsFE($idFrame);
        return $this->render("Structure.Frame.FERelation.grid");
    }

    #[Post(path: '/semantictype/{idFrame}/fes/relations')]
    public function feRelationsNew(string $idFrame)
    {
        try {
            FrameService::newInternalRelationFE($this->data);
            $this->trigger('reload-gridFEInternalRelation');
            return $this->renderNotify("success", "Relation created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/semantictype/fes/relations/{idEntityRelation}')]
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

    #[Get(path: '/semantictype/{idFrame}/semanticTypes')]
    public function semanticTypes(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->frame = new Frame($idFrame);
        return $this->render("semanticTypes");
    }

    #[Get(path: '/semantictype/{idFrame}/semanticTypes/formAdd')]
    public function semanticTypesAdd(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        return $this->render("Structure.Frame.SemanticType.formAdd");
    }

    #[Get(path: '/semantictype/{idFrame}/semanticTypes/grid')]
    public function semanticTypesGrid(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->relations = FrameService::listSemanticTypes($idFrame);
        return $this->render("Structure.Frame.SemanticType.grid");
    }

    #[Post(path: '/semantictype/{idFrame}/semanticTypes')]
    public function addSemanticType(int $idFrame)
    {
        try {
            $this->data->new->idFrame = $idFrame;
            $this->trigger('reload-gridSTRelation');
            return $this->renderNotify("success", "Semantic Type added.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/semantictype/semanticTypes/{idEntityRelation}')]
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
