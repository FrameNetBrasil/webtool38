<?php

namespace App\Models;

use Orkester\Persistence\Model;

class Color extends Model
{

    public ?int $idColor;
    public ?string $name;
    public ?int $rgbFg;
    public ?int $rgbBg;

}
