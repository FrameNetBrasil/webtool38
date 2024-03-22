<?php

namespace App\Models;

use Orkester\Persistence\Model;

class GenericLabelModel extends Model
{

    public ?int $idGenericLabel;
    public ?int $name;
    public ?int $definition;
    public ?int $example;
    public ?int $idEntity;
    public ?int $idColor;
    public ?int $idLanguage;
    public ?object $entity;
    public ?object $color;
    public ?object $language;

}
