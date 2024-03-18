<?php


namespace App\Models;

use Orkester\Persistence\Model;

class ConstraintType extends Model
{

    public ?int $idConstraintType;
    public ?string $entry;
    public ?string $prefix;
    public ?int $idTypeInstance;
    public ?int $idRelationGroup;

}
