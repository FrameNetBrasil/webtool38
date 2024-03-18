<?php

namespace App\Models;

use Orkester\Persistence\Model;

class Group extends Model
{

    public ?int $idGroup;
    public ?string $name;
    public ?string $description;
}

