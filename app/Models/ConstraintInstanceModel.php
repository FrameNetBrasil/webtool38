<?php

namespace App\Models;


use Orkester\Persistence\Model;

class ConstraintInstanceModel extends Model
{

    public ?int $idConstraintInstance;
    public ?int $idConstraintType;
    public ?int $idConstraint;
    public ?int $idConstrained;
    public ?int $idConstrainedBy;
    public ?object $entityConstraint;
    public ?object $entityConstrained;
    public ?object $entityConstrainedBy;
    public ?object $constrainedFE;

}
