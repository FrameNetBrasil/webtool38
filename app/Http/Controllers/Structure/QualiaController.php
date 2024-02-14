<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Repositories\Domain;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\Qualia;
use App\Services\AppService;
use App\Services\EntryService;
use App\Services\qualiaervice;
use App\Services\QualiaService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'auth')]
class QualiaController extends Controller
{

    #[Get(path: '/qualia')]
    public function browse()
    {
        $this->data->_token = csrf_token();
        return $this->render('browse');
    }

    #[Get(path: '/qualia/new')]
    public function new()
    {
        return $this->render("formNew");
    }

    #[Post(path: '/qualia')]
    public function newQualiaStructure()
    {
        try {
            $qualia = new Qualia();
            $qualia->create($this->data->new);
            $this->data->qualia = $qualia;
            return $this->clientRedirect("/qualia/{$qualia->idQualia}/edit");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/qualia/grid')]
    public function grid()
    {
        $this->data->_token = csrf_token();
        return $this->render("grid");
    }

    #[Get(path: '/qualia/listRelationsForSelect')]
    public function listRelationsForSelect()
    {
        return QualiaService::listRelationsForSelect();
    }

    #[Get(path: '/qualia/listTypesForSelect')]
    public function listTypesForSelect()
    {
        return QualiaService::listTypesForSelect();
    }

    #[Post(path: '/qualia/listForGrid')]
    public function listForGrid() {
        return QualiaService::listForGrid();
    }
    #[Get(path: '/qualia/{id}/edit')]
    public function edit(string $id)
    {
        $this->data->frame = new Frame($id);
        return $this->render("edit");
    }

    #[Get(path: '/qualia/{idFrame}/formEntries')]
    public function formEntries(string $idFrame)
    {
        $this->data->frame = new Frame($idFrame);
        $entry = new Entry();
        $this->data->entries = $entry->listByIdEntity($this->data->frame->idEntity);
        $this->data->languages = AppService::availableLanguages();
        return $this->render("formEntries");
    }

    #[Put(path: '/qualia/{idFrame}/entries')]
    public function entries(int $idFrame)
    {
        try {
            EntryService::updateEntries($this->data);
            return $this->renderNotify("success", "Translations recorded.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/qualia/{idFrame}/fes')]
    public function fes(string $idFrame)
    {
        $this->data->frame = new Frame($idFrame);
        return $this->render("fes");
    }

    #[Get(path: '/qualia/{idFrame}/fes/formNew')]
    public function formNewFE(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        return $this->render("Structure.FE.formNew");
    }

    #[Get(path: '/qualia/{idFrame}/fes/grid')]
    public function gridFE(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->fes = qualiaervice::listFEForGrid($idFrame);
        return $this->render("Structure.FE.grid");
    }

    #[Get(path: '/qualia/{idFrame}/lus')]
    public function lus(string $idFrame)
    {
        $this->data->frame = new Frame($idFrame);
        return $this->render("lus");
    }

    #[Get(path: '/qualia/{idFrame}/lus/formNew')]
    public function formNewLU(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        return $this->render("Structure.LU.formNew");
    }

    #[Get(path: '/qualia/{idFrame}/lus/grid')]
    public function gridLU(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->lus = qualiaervice::listLUForGrid($idFrame);
        return $this->render("Structure.LU.grid");
    }
    #[Get(path: '/qualia/{id}/classification')]
    public function classification(string $id)
    {
    }

    #[Get(path: '/qualia/{idFrame}/relations')]
    public function relations(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->frame = new Frame($idFrame);
        return $this->render("relations");
    }

    #[Get(path: '/qualia/{idFrame}/relations/formNew')]
    public function formNewRelation(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        return $this->render("formRelationNew");
    }


    #[Get(path: '/qualia/{idFrame}/relations/grid')]
    public function gridRelation(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->relations = qualiaervice::listRelations($idFrame);
        return $this->render("gridRelations");
    }

    #[Post(path: '/qualia/{idFrame}/relations')]
    public function newRelation(int $idFrame)
    {
        try {
            $this->data->new->idFrame = $idFrame;
            qualiaervice::newRelation($this->data->new);
            $this->trigger('reload-gridRelation');
            return $this->renderNotify("success", "Relation created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/qualia/relations/{idEntityRelation}')]
    public function deleteRelation(int $idEntityRelation)
    {
        try {
            qualiaervice::deleteRelation($idEntityRelation);
            $this->trigger('reload-gridRelation');
            return $this->renderNotify("success", "Relation deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/qualia/{idFrame}/fes/relations')]
    public function fesRelations(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        return $this->render("fesRelations");
    }

    #[Get(path: '/qualia/{idFrame}/fes/relations/formNew')]
    public function fesRelationsFormNew(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        return $this->render("fesRelationsFormNew");
    }

    #[Get(path: '/qualia/{idFrame}/fes/relations/grid')]
    public function fesRelationsGrid(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->relations = qualiaervice::listInternalRelationsFE($idFrame);
        return $this->render("fesRelationsGrid");
    }
    #[Post(path: '/qualia/{idFrame}/fes/relations')]
    public function feRelationsNewn(string $idFrame)
    {
        try {
            ddump($this->data);
            qualiaervice::newInternalRelationFE($this->data);
            $this->trigger('reload-gridFEInternalRelation');
            return $this->renderNotify("success", "Relation created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/qualia/fes/relations/{idEntityRelation}')]
    public function fesRelationDelete(int $idEntityRelation)
    {
        try {
            qualiaervice::deleteRelation($idEntityRelation);
            $this->trigger('reload-gridFEInternalRelation');
            return $this->renderNotify("success", "Relation deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }
    #[Get(path: '/qualia/{idFrame}/semanticTypes')]
    public function semanticTypes(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->frame = new Frame($idFrame);
        return $this->render("semanticTypes");
    }

    #[Get(path: '/qualia/{idFrame}/semanticTypes/formAdd')]
    public function semanticTypesAdd(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        return $this->render("semanticTypesFormAdd");
    }

    #[Get(path: '/qualia/{idFrame}/semanticTypes/grid')]
    public function semanticTypesGrid(string $idFrame)
    {
        $this->data->idFrame = $idFrame;
        $this->data->relations = qualiaervice::listSemanticTypes($idFrame);
        ddump($this->data);
        return $this->render("semanticTypesGrid");
    }

    #[Post(path: '/qualia/{idFrame}/semanticTypes')]
    public function addSemanticType(int $idFrame)
    {
        try {
            $this->data->new->idFrame = $idFrame;
            //qualiaervice::addSemanticType($this->data->new);
            $this->trigger('reload-gridSTRelation');
            return $this->renderNotify("success", "Semantic Type added.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/qualia/semanticTypes/{idEntityRelation}')]
    public function deleteSemanticType(int $idEntityRelation)
    {
        try {
            qualiaervice::deleteRelation($idEntityRelation);
            $this->trigger('reload-gridSTRelation');
            return $this->renderNotify("success", "Semantic Type deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }
}
