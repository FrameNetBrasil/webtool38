<?php

namespace App\Models;

use Orkester\Persistence\Model;

class GroupModel extends Model
{

    public ?int $idGroup;
    public ?string $name;
    public ?string $description;
}

