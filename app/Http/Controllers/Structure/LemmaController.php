<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Services\LemmaService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;

#[Middleware(name: 'auth')]
class LemmaController extends Controller
{
    #[Get(path: '/lemmas/listForSelect')]
    public function listForSelect()
    {
        return LemmaService::listForSelect();
    }

}
