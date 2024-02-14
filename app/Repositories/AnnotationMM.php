<?php

namespace App\Repositories;

use App\Models\AnnotationMMModel;
use Maestro\Persistence\Repository;

class AnnotationMM extends Repository
{
    public ?int $idAnnotationMM;
    public ?int $idObjectSentenceMM;
    public ?int $idFrameElement;
    public ?object $objectSentenceMM;
    public ?object $frameElement;
    public ?object $frame;

    public function __construct(int $id = null)
    {
        parent::__construct(AnnotationMMModel::class, $id);
    }

    public function getByObjectSentenceMM(int $idObjectSentenceMM) {
        $criteria = $this->getCriteria()
            ->select('*')
            ->where("idObjectSentenceMM","=",$idObjectSentenceMM);
        $this->retrieveFromCriteria($criteria);
    }

}
