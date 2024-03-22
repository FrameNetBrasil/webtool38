<?php
namespace App\Models;

use Orkester\Persistence\Model;

class EntryModel extends Model {

    public ?int $idEntry;
    public ?string $entry;
    public ?string $name;
    public ?string $description;
    public ?string $nick;
    public ?int $idEntity;
    public ?object $language;
    public ?object $entity;

}

