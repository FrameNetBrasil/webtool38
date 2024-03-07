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
        $criteria = $this->getCriteria()
            ->where("sentence.documents.idDocument","=", $idDocument)
            ->select(['idDynamicSentenceMM','startTime','endTime','idSentence','sentence.text']);
        debug($criteria->get()->all());
        return $criteria->getResult();
    }
}
