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
use App\Services\LemmaService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;
use Illuminate\Support\Facades\Request;

#[Middleware(name: 'auth')]
class LexiconController extends Controller
{
    #[Get(path: '/lexicon')]
    public function browse()
    {
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        $this->data->search->idLanguage = AppService::getCurrentIdLanguage();
        return $this->render('pageBrowse');
    }

    #[Post(path: '/lexicon/grid')]
    public function grid()
    {
        $this->data->search->_token = csrf_token();
        $response = $this->render("slotGrid");
        $query = [
            'search_lemma' => $this->data->search->lemma,
            'search_lexeme' => $this->data->search->lexeme,
            'search_idLanguage' => $this->data->search->idLanguage,
        ];
        $response->header('HX-Replace-Url', '/lexicon?' . http_build_query($query));
        return $response;
    }
    #[Post(path: '/lexicon/listForTree')]
    public function listForTree()
    {
        return LemmaService::listForTree();
    }
}
