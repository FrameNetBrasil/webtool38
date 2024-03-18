<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Repository;

class WordForm extends Repository
{
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idWordForm');
        if ($filter->idWordForm) {
            $criteria->where("idWordForm LIKE '{$filter->idWordForm}%'");
        }
        return $criteria;
    }

    public function listLUByWordForm($wordform)
    {
        $criteria = $this->getCriteria();
        $criteria->select('lexeme.lexemeentries.lemma.lus.idLU');
        //$criteria->where("upper(form) = upper('{$wordform}')");
        $criteria->where("form = lower('{$wordform}') OR (form LIKE lower('{$wordform}-%')) OR (form LIKE lower('%-{$wordform}'))");
        $lus = $criteria->asQuery()->chunkResult('idLU', 'idLU');
        if (count($lus) > 0) {
            $lu = new LU();
            //$criteria = $lu->getCriteria()->select("idLU, concat(frame.entries.name,'.',name) as fullName, locate(' ', name) as mwe");
            $criteria = $lu->getCriteria()->select("idLU, concat(frame.entries.name,'.',name) as fullName, count(lemma.lexemeentries.idLexemeEntry)-1 as mwe");
            //Base::relation($criteria, 'LU', 'Frame frame', 'rel_evokes');
            Base::entryLanguage($criteria, 'frame');
            $criteria->where("idLU", "IN", $lus);
            $criteria->where("lemma.idLanguage", "=", "frame.entries.idLanguage");
            $criteria->groupBy("idLU, concat(entry.name,'.',lu.name)");
            //return $criteria->asQuery()->chunkResult('idLU', 'fullName');
            return $criteria->asQuery()->asObjectArray();
        } else {
            return [];
        }
    }

    public function lookFor($words)
    {
        $criteria = $this->getCriteria()->select('form as i, form');
        $criteria->where("form", "in", $words);
        return $criteria->asQuery()->chunkResult('i', 'form');
    }

    public function listForLookup($wordform = '')
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $form = trim($wordform);
        $form = (strlen($wordform) == strlen($form)) ? $form . '%' : $form;
        $criteria = $this->getCriteria()->select("idWordForm, concat(form, '  [', lexeme.name, '  ', lexeme.pos.entries.name,']','  [',lexeme.language.language,']') as fullname")->orderBy('form');
        $criteria->where("lexeme.idLanguage = {$idLanguage}");
        $criteria->where("lexeme.pos.entries.idLanguage = {$idLanguage}");
        $criteria->where("form LIKE '{$form}'");
        return $criteria;
    }

    public function listLexemes($words)
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $criteria = $this->getCriteria()->select('form, lexeme.name as lexeme, lexeme.pos.POS as POSLexeme');
        $criteria->where("form", "in", $words);
        $criteria->where("lexeme.idLanguage", "=", $idLanguage);
        return $criteria->asQuery();
    }

    public function save(): ?int
    {
        parent::save();
        Timeline::addTimeline("wordform", $this->getId(), "S");
        return $this->getId();
    }

    public function saveOffline()
    {
        parent::save();
        Timeline::addTimeline("wordform", $this->getId(), "S");
    }

    public function hasLU($wordformList)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $list = [];
        foreach ($wordformList as $i => $wf) {
            if ($wf != '') {
                $wf1 = str_replace("'", "\'", $wf);
                $criteria = $this->getCriteria();
                $criteria->select(['form', 'count(lexeme.lexemeEntries.lemma.lus.idLU) as n']);
                $criteria->where("form", "=", $wf1);
                $criteria->where("lexeme.lexemeEntries.lemma.idLanguage", "=", $idLanguage);
                $criteria->groupBy("form");
                $criteria->having("count(lexeme.lexemeEntries.lemma.lus.idLU)", ">", 0);
                $r = $criteria->asQuery()->getResult();
                if (count($r)) {
                    $list[$wf] = $r[0]['n'];
                }
            }
        }
        return $list;
    }

    public function listLU($wordformList)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $list = [];
        foreach ($wordformList as $i => $wf) {
            if ($wf != '') {
                $wf1 = str_replace("'", "\'", $wf);
                $criteria = $this->getCriteria()
                    ->distinct();
                $criteria->select([
                    'lexeme.lexemeEntries.lemma.lus.idLU',
                    'lexeme.lexemeEntries.lemma.lus.name',
                    'lexeme.lexemeEntries.lemma.lus.frame.name as frameName'
                ]);
                $criteria->where("form", "=", $wf1);
                $criteria->where("lexeme.lexemeEntries.lemma.idLanguage", "=", $idLanguage);
                $criteria->where("lexeme.lexemeEntries.lemma.lus.frame.idLanguage", "=", $idLanguage);
                $criteria->where("lexeme.lexemeEntries.headWord", "=", 1);
                $criteria->orderBy("lexeme.lexemeEntries.lemma.lus.frame.name,lexeme.lexemeEntries.lemma.lus.name");
//                $criteria->groupBy("form");
//                $criteria->having("count(lexeme.lexemeEntries.lemma.lus.idLU)", ">", 0);
                $r = $criteria->asQuery()->getResult();
                if (count($r)) {
                    $list[$wf] = $r;
                }
            }
        }
        return $list;
    }

}

