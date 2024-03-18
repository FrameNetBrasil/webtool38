<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class UserAnnotation extends Repository
{
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
