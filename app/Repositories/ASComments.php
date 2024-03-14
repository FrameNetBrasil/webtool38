<?php

namespace App\Repositories;

use App\Models\ASCommentsModel;
use Orkester\Persistence\Repository;

class ASComments extends Repository
{

    public ?int $idASComments;
    public ?string $extraThematicFE;
    public ?string $extraThematicFEOther;
    public ?string $comment;
    public ?string $construction;
    public ?int $idAnnotationSet;
    public ?object $annotationSet;

    public function __construct(int $id = null)
    {
        parent::__construct(ASCommentsModel::class, $id);
    }

    public function getDescription()
    {
        return $this->getIdASComments();
    }

    public function getByAnnotationSet($idAnnotationSet)
    {
        $criteria = $this->getCriteria()->select('*');
        $criteria->where("idAnnotationSet = {$idAnnotationSet}");
        $this->retrieveFromCriteria($criteria);
    }

    public function deleteByAnnotationSet(int $idAnnotationSet)
    {
        $this->getCriteria()
            ->where("idAnnotationSet", "=", $idAnnotationSet)
            ->delete();
    }
}

