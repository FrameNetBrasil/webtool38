<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Repositories\Domain;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\SemanticType;
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
    /***
     * Child
     */
    #[Get(path: '/semanticType/{idEntity}/childAdd/{root}')]
    public function childFormAdd(string $idEntity, string $root)
    {
        $this->data->idEntity = $idEntity;
        $this->data->root = $root;
        return $this->render("Structure.SemanticType.childAdd");
    }

    #[Get(path: '/semanticType/{idEntity}/childGrid')]
    public function childGrid(string $idEntity)
    {
        $semanticType = new SemanticType();
        $this->data->idEntity = $idEntity;
        $this->data->relations = $semanticType->listRelations($idEntity)->getResult();
        return $this->render("Structure.SemanticType.childGrid");
    }

    #[Post(path: '/semanticType/{idEntity}/add')]
    public function childAdd(int $idEntity)
    {
        try {
            $semanticType = new SemanticType($this->data->new->idSemanticType);
            $semanticType->add($idEntity);
            $this->trigger('reload-gridChildST');
            return $this->renderNotify("success", "Semantic Type added.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/semanticType/{idEntityRelation}')]
    public function childDelete(int $idEntityRelation)
    {
        try {
            $relation = new EntityRelation($idEntityRelation);
            $relation->delete();
            $this->trigger('reload-gridChildST');
            return $this->renderNotify("success", "Semantic Type deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    /***
     * main
     */
}
