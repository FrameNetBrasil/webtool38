<?php

namespace App\Models;

use Orkester\Persistence\Model;

class DynamicBBoxMM extends Model
{
    public ?int $idDynamicBBoxMM;
    public ?int $frameNumber;
    public ?float $frameTime;
    public ?int $x;
    public ?int $y;
    public ?int $width;
    public ?int $height;
    public ?int $blocked;
    public ?int $idDynamicObjectMM;

}
