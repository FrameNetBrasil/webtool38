<?php

namespace App\Models;

use Orkester\Persistence\Model;

class Frame extends Model
{

    public ?int $idFrame;
    public ?string $entry;
    public ?int $active;
    public ?int $idEntity;
    public ?string $name;
    public ?string $description;
    public ?int $idLanguage;
    public ?object $entity;
    public ?array $lus;
    public ?array $fes;
    public ?array $entries;
    public ?array $relations;
    public ?array $inverseRelations;

}