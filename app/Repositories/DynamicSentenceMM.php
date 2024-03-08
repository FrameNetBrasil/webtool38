<?php

namespace App\Repositories;

use App\Models\DynamicSentenceMMModel;
use Maestro\Persistence\Repository;

class DynamicSentenceMM extends Repository
{
    public ?int $idDynamicSentenceMM;
    public ?int $startTime;
    public ?int $endTime;
    public ?int $origin;
    public ?int $idSentence;
    public ?int $idOriginMM;
    public ?object $sentence;
    public ?object $originMM;

    public function __construct(int $id = null)
    {
        parent::__construct(DynamicSentenceMMModel::class, $id);
    }

    public function listByDocument($idDocument): array
    {
        $cmd = <<<HERE
select `dynamicsentencemm`.`idDynamicSentenceMM`,
       `dynamicsentencemm`.`startTime`,
       `dynamicsentencemm`.`endTime`,
       `dynamicsentencemm`.`idSentence`,
       `sentence_1`.`text`
from `dynamicsentencemm`
         inner join `sentence` as `sentence_1` on `dynamicsentencemm`.`idSentence` = `sentence_1`.`idSentence`
         inner join `document_sentence` as `a3` on `sentence_1`.`idSentence` = `a3`.`idSentence`
         inner join `document` as `documents_2` on `a3`.`idDocument` = `documents_2`.`idDocument`
where `documents_2`.`idDocument` = {$idDocument}

HERE;
        return $this->query($cmd);
    }
}
