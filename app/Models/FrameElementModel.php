<?php

namespace App\Models;

use Orkester\Persistence\Model;

class FrameElementModel extends Model
{

    public ?int $idFrameElement;
    public ?string $entry;
    public ?string $coreType;
    public ?int $active;
    public ?int $idEntity;
    public ?int $idFrame;
    public ?int $idColor;
    public ?string $name;
    public ?string $description;
    public ?int $idLanguage;
    public ?object $entity;
    public ?object $frame;
    public ?object $color;
    public ?array $entries;
    public ?array $relations;

}

