<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Repositories\EntityRelation;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Illuminate\Http\Request;

#[Middleware(name: 'auth')]
class RelationController extends Controller
{
    #[Delete(path: '/relations/{idEntityRelation}')]
    public function delete(string $idEntityRelation)
    {
        try {
            $relation = new EntityRelation($idEntityRelation);
            $relation->delete();
            return $this->renderNotify("success", "Relation deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }
}
