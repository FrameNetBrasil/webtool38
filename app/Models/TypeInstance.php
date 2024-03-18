<?php

namespace App\Models;

use Orkester\Persistence\Model;

class TypeInstance extends Model
{

    public ?int $idTypeInstance;
    public ?string $entry;
    public ?string $info;
    public ?int $flag;
    public ?int $idType;
    public ?int $idEntity;
    public ?int $idColor;
    public ?string $name;
    public ?string $description;
    public ?int $idLanguage;
    public ?object $entity;
    public ?object $color;
    public ?object $type;

}
