<?php

namespace App\Models;

use Orkester\Persistence\Model;
class Layer extends Model
{
    public ?int $idLayer;
    public ?int $rank;
    public ?int $idLayerType;
    public ?int $idAnnotationSet;
    public ?object $layerType;
    public ?object $annotationSet;
    public ?array $labels;

}

