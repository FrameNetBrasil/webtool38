<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Services\LemmaService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'auth')]
class LemmaController extends Controller
{
    #[Get(path: '/lemma/listForSelect')]
    public function listForSelect()
    {
        return LemmaService::listForSelect();
    }

    #[Post(path: '/lemma/listForTree')]
    public function listForTree()
    {
        return LemmaService::listForTree();
    }

}
