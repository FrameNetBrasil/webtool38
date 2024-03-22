<?php
namespace App\Models;

use Orkester\Persistence\Model;

class RelationGroupModel extends Model
{

    public ?int $idRelationGroup;
    public ?string $entry;
    public ?int $idEntity;
    public ?string $name;
    public ?string $description;
    public ?int $idLanguage;
    public ?array $entries;
    public ?object $entity;

}

