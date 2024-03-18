<?php
namespace App\Repositories;

use Orkester\Persistence\Repository;

class ViewWfLexemeLemma extends Repository {

    public function listByFilter($filter = NULL)
    {
        $criteria = $this->getCriteria()->select('idWordForm, form, idLexeme, lexeme, idPOSLexeme, POSLexeme, idLanguage, idLexemeEntry, lexemeOrder, breakBefore, headWord, idLemma, lemma, idPOSLemma, POSLemma, language');
        if (is_null($filter)) {
            $criteria->where("form = ''");
        } else {
            if ($filter->form != '') {
                $criteria->where("form = '{$filter->form}'");
            }
            if ($filter->lexeme != '') {
                $criteria->where("lexeme = '{$filter->lexeme}'");
            }
            if ($filter->arrayForm != '') {
                $criteria->where("form", "in", $filter->arrayForm);
            }
            if ($filter->idLanguage != '') {
                $criteria->where("idLanguage = {$filter->idLanguage}");
            }
        }
        return $criteria;
    }


}

