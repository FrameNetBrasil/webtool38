<?php

namespace App\Models;

use Orkester\Persistence\Model;

class ViewAnnotationSetModel extends Model
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

}

