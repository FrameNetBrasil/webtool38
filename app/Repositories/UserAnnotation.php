<?php

namespace App\Repositories;

use App\Models\UserAnnotationModel;
use Orkester\Persistence\Repository;

class UserAnnotation extends Repository
{
    public ?int $idUserAnnotation;
    public ?int $idUser;
    public ?int $idSentenceStart;
    public ?int $idSentenceEnd;
    public ?int $idDocument;
    public ?object $document;
    public ?object $sentenceStart;
    public ?object $sentenceEnd;
    public ?object $user;

    public function __construct(int $id = null)
    {
        parent::__construct(UserAnnotationModel::class, $id);
    }

    public function listSentenceByUser($idUser, $idDocument) {
        $cmd = <<<HERE
select s.idSentence
from userannotation ua
join sentence s on ((s.idSentence >= ua.idSentenceStart) and (s.idSentence <= ua.idSentenceEnd))
join document_sentence ds on (s.idSentence = ds.idSentence)
where (ds.idDocument = {$idDocument})
and (ua.idUser = {$idUser})

HERE;
        return array_column($this->query($cmd), 'idSentence');
    }
}
