<?php


namespace App\Models;

use Orkester\Persistence\Model;

class Type extends Model
{
    public ?int $idType;
    public ?string $entry;
    public ?int $idEntity;
    public ?string $name;
    public ?string $description;
    public ?int $idLanguage;
    public ?object $entity;
    public ?array $entries;

}
