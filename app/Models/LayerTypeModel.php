<?php

namespace App\Models;

use Orkester\Persistence\Model;

class LayerTypeModel extends Model
{
    public ?int $idLayerType;
    public ?string $entry;
    public ?int $allowsApositional;
    public ?int $isAnnotation;
    public ?int $layerOrder;
    public ?int $idLayerGroup;
    public ?int $idEntity;
    public ?string $name;
    public ?string $description;
    public ?int $idLanguage;
    public ?array $entries;
    public ?object $entity;
    public ?object $layerGroup;

}

