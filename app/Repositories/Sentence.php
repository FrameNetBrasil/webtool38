<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class Sentence extends Repository
{
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idSentence');
        if ($filter->idSentence) {
            $criteria->where("idSentence LIKE '{$filter->idSentence}%'");
        }
        if ($filter->idDocument) {
            $criteria->where("idDocument =  {$filter->idDocument}");
        }
        return $criteria;
    }

    public function save(): ?int
    {
        parent::save();
    }

    public function delete()
    {
        $cmd = <<<HERE

select s.idSentenceMM
FROM sentenceMM s
where (s.idSentence = {$this->getId()})

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->getResult();
        $cmd3 = "delete from SentenceMM where idSentence = {$this->getId()}";
        $this->getDb()->executeCommand($cmd3);
        $cmd4 = "delete from AnnotationSet where idSentence = {$this->getId()}";
        $this->getDb()->executeCommand($cmd4);
        parent::delete();
    }

    public function hasAnnotation()
    {
        return (count($this->getAnnotationsets()) > 0);
    }

}
