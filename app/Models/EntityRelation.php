<?php

namespace App\Models;

use Orkester\Persistence\Model;

class EntityRelation extends Model
{

    public ?int $idEntityRelation;
    public ?int $idRelationType;
    public ?string $entry;
    public ?string $entity1Type;
    public ?string $entity2Type;
    public ?string $entity3Type;
    public ?int $idEntity1;
    public ?int $idEntity2;
    public ?int $idEntity3;
    public ?int $idRelation;
    public ?object $relationType;

}
