<?php

namespace App\Http\Controllers\Structure;

use App\Data\SearchLexiconData;
use App\Http\Controllers\Controller;
use App\Repositories\Domain;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\Lemma;
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
        data('search', session('searchLexicon') ?? SearchLexiconData::from());
        return $this->render('browse');
    }

    #[Post(path: '/lexicon/grid')]
    public function grid()
    {
        data('search', SearchLexiconData::from(data('search')));
        session(['searchLexicon' => $this->data->search]);
        return $this->render("grid");
    }
    #[Get(path: '/lexicon/listForSelect')]
    public function listForSelect()
    {
        $lemma = new Lemma();
        return $lemma->listForSelect(data('q'))->getResult();
    }

    #[Post(path: '/lexicon/listForTree')]
    public function listForTree()
    {
        $search = SearchLexiconData::from($this->data);
        $result = [];
        $id = data('id', default:'');
        if ($id != '') {
            $idLemma = substr($id, 1);
            $resultLexeme = LexemeController::listForTreeByLemma($idLemma);
            return $resultLexeme;
        } else {
            $icon = 'material-icons-outlined wt-tree-icon wt-icon-lemma';
            if ($search->lexeme == '') {
                $lemma = new Lemma();
                $lemmas = $lemma->listByFilter($search)->getResult();
                foreach ($lemmas as $row) {
                    $node = [];
                    $node['id'] = 'l' . $row['idLemma'];
                    $node['type'] = 'lemma';
                    $node['name'] = $row['name'];
                    $node['state'] = 'closed';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-lemma';
                    $node['children'] = [];
                    $result[] = $node;
                }
            } else {
                if ($search->lexeme != '') {
                    $result = LexemeController::listForTreeByName($search->lexeme);
                    $icon = "material-icons wt-tree-icon wt-icon-lexeme";
                }
            }
            $total = count($result);
            return [
                'total' => $total,
                'rows' => $result,
                'footer' => [
                    [
                        'type' => 'lemma',
                        'name' => ["{$total} record(s)", ''],
                        'iconCls' => $icon
                    ]
                ]
            ];
        }
    }

}
