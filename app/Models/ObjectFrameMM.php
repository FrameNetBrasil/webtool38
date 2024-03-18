<?php

namespace App\Models;

use Orkester\Persistence\Model;

class ObjectFrameMM extends Model
{

    public ?int $idObjectFrameMM;
    public ?int $frameNumber;
    public ?int $frameTime;
    public ?int $x;
    public ?int $y;
    public ?int $width;
    public ?int $height;
    public ?int $blocked;
    public ?int $idObjectMM;


}
