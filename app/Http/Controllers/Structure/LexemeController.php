<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Repositories\Lexeme;
use App\Services\LemmaService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'auth')]
class LexemeController extends Controller
{
    public static function listForTreeByLemma(int $idLemma): array
    {
        $result = [];
        $lexeme = new Lexeme();
        $lexemes = $lexeme->listByFilter((object)['idLemma' => $idLemma])->asQuery()->getResult();
        foreach ($lexemes as $lexeme) {
            $node = [];
            $node['id'] = 'x' . $lexeme['idLexeme'];
            $node['idLexeme'] = $lexeme['idLexeme'];
            $node['type'] = 'lexeme';
            $node['name'] = $lexeme['name'] . $lexeme['POS'];
            $node['state'] = 'closed';
            $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-lexeme';
            $node['children'] = [];
            $result[] = $node;
        }
        return $result;
    }
}
