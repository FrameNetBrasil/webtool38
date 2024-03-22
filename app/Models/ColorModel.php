<?php

namespace App\Models;

use Orkester\Persistence\Model;

class ColorModel extends Model
{

    public ?int $idColor;
    public ?string $name;
    public ?int $rgbFg;
    public ?int $rgbBg;

}
