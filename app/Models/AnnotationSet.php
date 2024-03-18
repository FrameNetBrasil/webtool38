<?php

namespace App\Models;

use Orkester\Persistence\Model;

class AnnotationSet extends Model
{
    public ?int $idAnnotationSet;
    public ?int $idSentence;
    public ?int $idAnnotationStatus;
    public ?int $idEntityRelated;
    public ?int $idEntityLU;
    public ?int $idEntityCxn;
    public ?array $lu;
    public ?array $cxn;
    public ?object $sentence;
    public ?object $annotationStatus;
    public ?array $layers;

}
