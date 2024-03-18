<?php

namespace App\Models;

use Orkester\Persistence\Model;

class SemanticType extends Model
{

    public ?int $idSemanticType;
    public ?string $entry;
    public ?int $idEntity;
    public ?int $idDomain;
    public ?string $name;
    public ?string $description;
    public ?string $idLanguage;
    public ?object $entity;
    public ?array $entries;
    public ?array $relations;
    public ?array $inverseRelations;

}

