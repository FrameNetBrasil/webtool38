<?php

namespace App\Models;

use Orkester\Persistence\Model;

class RelationType extends Model
{

    public ?int $idRelationType;
    public ?string $entry;
    public ?string $prefix;
    public ?string $nameEntity1;
    public ?string $nameEntity2;
    public ?string $nameEntity3;
    public ?int $idDomain;
    public ?int $idEntity;
    public ?int $idTypeInstance;
    public ?int $idRelationGroup;
    public ?string $name;
    public ?string $description;
    public ?string $idLanguage;
    public ?object $entity;
    public ?object $typeInstance;
    public ?object $relationGroup;
    public ?array $entries;

}
