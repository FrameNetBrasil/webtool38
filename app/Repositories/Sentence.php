<?php

namespace App\Repositories;

use App\Models\SentenceModel;
use Maestro\Persistence\Repository;

class Sentence extends Repository
{


    public ?int $idSentence;
    public ?string $text;
    public ?int $paragraphOrder;
    public ?int $idParagraph;
    public ?int $idLanguage;
    public ?int $idDocument;
    public ?object $paragraph;
    public ?object $document;
    public ?object $language;
    public ?array $sentenceMM;
    public ?array $documents;

    public function __construct(int $id = null)
    {
        parent::__construct(SentenceModel::class, $id);
    }

    public function getDescription()
    {
        return $this->getIdSentence();
    }

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