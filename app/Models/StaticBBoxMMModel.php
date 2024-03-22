<?php

namespace App\Models;

use Orkester\Persistence\Model;

class StaticBBoxMMModel extends Model
{
    public ?int $idStaticBBoxMM;
    public ?int $x;
    public ?int $y;
    public ?int $width;
    public ?int $height;
    public ?int $idStaticObjectMM;
    public ?object $staticObjectMM;

}
