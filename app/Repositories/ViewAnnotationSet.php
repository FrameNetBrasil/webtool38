<?php

namespace App\Repositories;

use App\Models\ViewAnnotationSetModel;
use App\Services\AppService;
use Maestro\Persistence\Repository;

class ViewAnnotationSet extends Repository
{

    public ?int $idAnnotationSet;
    public ?int $idSentence;
    public ?string $entry;
    public ?int $idAnnotationStatus;
    public ?int $idLU;
    public ?int $idEntityLU;
    public ?int $idConstruction;
    public ?int $idEntityCxn;
    public ?array $lu;
    public ?array $cxn;
    public ?object $entries;
    public ?object $sentence;
    public ?object $annotationStatusType;
    public ?array $layers;

    public function __construct(int $id = null)
    {
        parent::__construct(ViewAnnotationSetModel::class, $id);
    }

    public function listTargetBySentence(int $idSentence)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $cmd = <<<HERE
        SELECT startChar,endChar,rgbFg,rgbBg,instantiationType
        FROM view_labelfecetarget
        WHERE (idSentence = {$idSentence})
            AND (layerTypeEntry = 'lty_target')
            AND (idLanguage = {$idLanguage} )
        ORDER BY idSentence,startChar

HERE;
        return $this->query($cmd);
    }


    /*
    public function listByLU($idLU, $sortable = NULL)
    {
        $criteria = $this->getCriteria()
            ->select('idAnnotationSet, idSentence, sentence.text, entries.name as annotationStatus, idAnnotationStatus, annotationstatustype.color.rgbBg')
//            ->where("subcorpuslu.idLU = {$idLU}");
            ->where("idLU = {$idLU}");
        if ($sortable) {
            if ($sortable->field == 'status') {
                $criteria->orderBy('entries.name ' . $sortable->order);
            }
            if ($sortable->field == 'idSentence') {
                $criteria->orderBy('idSentence ' . $sortable->order);
            }
        }
        Base::entryLanguage($criteria);
        return $criteria;
    }

    public function listByCxn($idCxn, $sortable = NULL)
    {
        $criteria = $this->getCriteria()
            ->select('idAnnotationSet, idSentence, sentence.text, entries.name as annotationStatus, idAnnotationStatus, annotationstatustype.color.rgbBg')
//            ->where("subcorpuscxn.idConstruction = {$idCxn}");
            ->where("idConstruction = {$idCxn}");
        if ($sortable) {
            if ($sortable->field == 'status') {
                $criteria->orderBy('entries.name ' . $sortable->order);
            }
            if ($sortable->field == 'idSentence') {
                $criteria->orderBy('idSentence ' . $sortable->order);
            }
        }
        Base::entryLanguage($criteria);
        return $criteria;
    }

    public function listByDocument($idDocument, $sortable = NULL)
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $sort = '';
        if ($sortable) {
            if ($sortable->field == 'status') {
                $sort .= ' ORDER BY entry.name ' . $sortable->order;
            } else if ($sortable->field == 'idSentence') {
                $sort .= ' ORDER BY idSentence ' . $sortable->order;
            }
        }
        $cmd = <<<HERE
SELECT sentence.idSentence,sentence.text, if(count(annotationset.idAnnotationSet) = 0, 5, 6) as idAnnotationStatus 
FROM sentence
JOIN document_sentence on (sentence.idSentence = document_sentence.idSentence)
LEFT JOIN annotationset ON (sentence.idSentence=annotationset.idSentence)
WHERE (document_sentence.idDocument = {$idDocument})
GROUP BY sentence.idSentence,sentence.text

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->getResult();
        return $result;
    }

    public function listSentencesByAS($idAnnotationSet)
    {
        $criteria = $this->getCriteria()->
        select('idAnnotationSet, idSentence, sentence.text, entries.name as annotationStatus, idAnnotationStatus, annotationstatustype.color.rgbBg');
        if (is_array($idAnnotationSet)) {
            $criteria->where('idAnnotationSet', 'IN', $idAnnotationSet);
        } else {
            $criteria->where('idAnnotationSet', '=', $idAnnotationSet);
        }
        $criteria->orderBy('idAnnotationSet');
        Base::entryLanguage($criteria);
        return $criteria;
    }

//    public function listFECEBySubCorpus($idSubCorpus) {
//        $idLanguage = \Manager::getSession()->idLanguage;
//        $cmd = <<<HERE
//        SELECT *
//        FROM view_labelfecetarget
//        WHERE (idSubCorpus = {$idSubCorpus})
//            AND (idLanguage = {$idLanguage} )
//        ORDER BY idSentence,startChar
//
//HERE;
//        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('idSentence', 'startChar,endChar,rgbFg,rgbBg,instantiationType');
//        return $result;
//    }

    public function listFECEByLU($idLU)
    {
        $idLanguage = \Manager::getSession()->idLanguage;
//        $cmd = <<<HERE
//SELECT *
//FROM view_labelfecetarget vl
//JOIN subcorpus on (subcorpus.idSubCorpus = vl.idSubCorpus)
//JOIN entityRelation er on (er.idEntity2 = subCorpus.idEntity)
//JOIN lu on (lu.idEntity = er.idEntity1)
//WHERE (lu.idLU ={$idLU})
//AND (idLanguage = {$idLanguage} )
//ORDER BY idSentence,startChar
//
//HERE;
        $cmd = <<<HERE
SELECT *
FROM view_labelfecetarget vl
JOIN view_annotationset a on (vl.idAnnotationSet = a.idAnnotationSet)    
JOIN lu on (lu.idEntity = a.idEntityLU)
WHERE (lu.idLU ={$idLU})
AND (idLanguage = {$idLanguage} )
ORDER BY vl.idSentence,vl.startChar

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('idSentence', 'startChar,endChar,rgbFg,rgbBg,instantiationType');
        return $result;
    }

    public function listFECEByDocument($idDocument)
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE
SELECT *
FROM view_labelfecetarget vl
JOIN sentence s on (vl.idSentence = s.idSentence)
JOIN paragraph p on (s.idParagraph = p.idParagraph)
WHERE (p.idDocument = {$idDocument})
AND (vl.idLanguage = {$idLanguage} )
ORDER BY vl.idSentence,vl.startChar

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('idSentence', 'startChar,endChar,rgbFg,rgbBg,instantiationType');
        return $result;
    }

    public function listFECEByAS($idAnnotationSet)
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        if (is_array($idAnnotationSet)) {
            $set = implode(',', $idAnnotationSet);
            $condition = "(idAnnotationSet IN ({$set}))";
        } else {
            $condition = "(idAnnotationSet = {$idAnnotationSet})";
        }
        $cmd = <<<HERE
        SELECT l.idSentence, l.startChar, l.endChar, l.rgbFg, l. rgbBg, l.instantiationType, fe.entry as feEntry, e.name feName, layerTypeEntry
        FROM view_labelfecetarget l left join view_frameelement fe on (l.idFrameElement = fe.idFrameElement)
        LEFT JOIN entry e on (fe.entry = e.entry)
        WHERE {$condition} AND (l.idLanguage = {$idLanguage} )
        AND ((e.idLanguage = {$idLanguage}) OR (e.idLanguage is null))
        ORDER BY idSentence,startChar

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('idSentence', 'startChar,endChar,rgbFg,rgbBg,instantiationType,feEntry,feName,layerTypeEntry');
        return $result;
    }


    public function listLUCountByLanguage()
    {
//        $cmd = <<<HERE
//select lu.idlanguage, l.language, count(distinct lu.name) as n
//from view_annotationset a
//join view_subcorpuslu slu on (a.idSubcorpus = slu.idSubCorpus)
//join view_lu lu on (slu.idLu = lu.idLU)
//join language l on (lu.idLanguage = l.idLanguage)
//group by lu.idlanguage, l.language
//order by 2
//
//HERE;
        $cmd = <<<HERE
select lu.idlanguage, l.language, count(distinct lu.name) as n
from view_annotationset a
join view_lu lu on (a.idLu = lu.idLU)
join language l on (lu.idLanguage = l.idLanguage)
group by lu.idlanguage, l.language
order by 2

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->getResult();
        return $result;
    }

    public function listASCountByLanguage()
    {
//        $cmd = <<<HERE
//select lu.idlanguage, l.language, count(distinct a.idAnnotationSet) as n
//from view_annotationset a
//join view_subcorpuslu slu on (a.idSubcorpus = slu.idSubCorpus)
//join view_lu lu on (slu.idLu = lu.idLU)
//join language l on (lu.idLanguage = l.idLanguage)
//group by lu.idlanguage, l.language
//order by 2
//
//HERE;
        $cmd = <<<HERE
select lu.idlanguage, l.language, count(distinct a.idAnnotationSet) as n
from view_annotationset a
join view_lu lu on (a.idLu = lu.idLU)
join language l on (lu.idLanguage = l.idLanguage)
group by lu.idlanguage, l.language
order by 2

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->getResult();
        return $result;
    }

    public function listCorpusASCountByLanguage($idCorpus)
    {
        $cmd = <<<HERE
select substr(s.documentEntry,9,5) as language, count(*) as n
from view_annotationset a
join view_sentence s on (a.idSentence = s.idSentence)
where s.idCorpus = {$idCorpus}
group by substr(s.documentEntry,9,5)
order by 1


HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->getResult();
        return $result;
    }

    public function listCountTargetInTextByLanguage()
    {
        $result = [];
        // count words in document by language
        $cmd = <<<HERE
select idLanguage, language
from language
order by language

HERE;
        $languages = $this->getDb()->getQueryCommand($cmd)->getResult();
        foreach ($languages as $language) {
            $idLanguage = $language['idLanguage'];
            $cmd = <<<HERE
select text
from view_sentence
where idLanguage = {$idLanguage}

HERE;
            $wordCount = 0;
            $sentences = $this->getDb()->getQueryCommand($cmd)->getResult();
            foreach ($sentences as $sentence) {
                $text = $sentence['text'];
                $words = explode(' ', $text);
                $wordCount += count($words);
            }
            $asCount = 0;
//            $cmd = <<<HERE
//select count(distinct a.idAnnotationSet) as n
//from view_annotationset a
//join view_subcorpuslu slu on (a.idSubcorpus = slu.idSubCorpus)
//join view_lu lu on (slu.idLu = lu.idLU)
//where lu.idLanguage = {$idLanguage}
//
//HERE;
            $cmd = <<<HERE
select count(distinct a.idAnnotationSet) as n
from view_annotationset a
join view_lu lu on (a.idLu = lu.idLU)
where lu.idLanguage = {$idLanguage}

HERE;
            $as = $this->getDb()->getQueryCommand($cmd)->getResult();
            $asCount = $as[0]['n'];
            if ($asCount > 0) {
                $result[] = [
                    'idLanguage' => $idLanguage,
                    'language' => $language['language'],
                    'n' => ($asCount / $wordCount)
                ];
            }
        }
        return $result;
    }

    ///
    /// Multimodal
    ///

    public function listByDocumentMM($idDocumentMM, $sortable = NULL)
    {

        $cmd = <<<HERE
select s.idSentence, sentenceMM.idSentenceMM, sentenceMM.startTimestamp, sentenceMM.endTimestamp, s.text
from view_sentence s 
join sentenceMM on (sentenceMM.idSentence = s.idSentence)
join documentMM on (documentMM.idDocument = s.idDocument)    
where (documentMM.idDocumentMM = {$idDocumentMM})
HERE;
        $as = $this->getDb()->getQueryCommand($cmd)->getResult();
        return $as;
    }
    */


}

