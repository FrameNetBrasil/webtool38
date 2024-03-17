<?php

namespace App\Services;

use App\Repositories\Lemma;
use App\Repositories\Lexeme;
use Orkester\Manager;


class LemmaService
{
    public static function listForSelect()
    {
        $data = Manager::getData();
        debug($data);
        $q = $data->q ?? '';
        $frame = new Lemma();
        debug($frame->listForSelect($q)->getResult());
        return $frame->listForSelect($q)->getResult();
    }

    public static function listForTree()
    {
        $data = Manager::getData();
        $result = [];
        $id = $data->id ?? '';
        if ($id != '') {
            $type = $id[0];
            if ($type == 'l') {
                $idLemma = substr($id, 1);
                $lemma = new Lemma($idLemma);
                $lexemes = $lemma->listLexemes($idLemma);
                foreach ($lexemes as $lexeme) {
                    $node = [];
                    $node['id'] = 'x' . $lexeme['idLexeme'];
                    $node['type'] = 'lexeme';
                    $node['name'] = $lexeme['name'];
                    $node['state'] = 'open';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-lexeme';
                    $node['children'] = [];
                    $result[] = $node;
                }
            }
            if ($type == 'x') {
                $idLexeme = substr($id, 1);
                $lexeme = new Lexeme($idLexeme);
                $words = $lexeme->listWordForms();
                foreach ($words as $word) {
                    $node = [];
                    $node['id'] = 'w' . $word['idWordform'];
                    $node['type'] = 'wordform';
                    $node['name'] = $word['form'];
                    $node['state'] = 'open';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-wordform';
                    $node['children'] = [];
                    $result[] = $node;
                }
            }
        } else {
            $filter = $data;
            if ($filter->lexeme ?? false) {
                $lexeme = new Lexeme();
                $lexemes = $lexeme->listByFilter($filter)->asQuery()->getResult();
                foreach ($lexemes as $row) {
                    $node = [];
                    $node['id'] = 'x' . $row['idLexeme'];
                    $node['type'] = 'lexeme';
                    $node['name'] = $row['name'];
                    $node['state'] = 'closed';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-lexeme';
                    $node['children'] = [];
                    $result[] = $node;
                }
            } else {
                if ($filter->lemma ?? false) {
                    $lemma = new Lemma();
                    debug($filter);
                    $lemmas = $lemma->listByFilter($filter)->asQuery()->getResult();
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
                }
            }
        }
        return $result;
    }

}
