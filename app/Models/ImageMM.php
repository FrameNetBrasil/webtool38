<?php

namespace App\Models;

use Orkester\Persistence\Model;

class ImageMM extends Model
{
    public ?int $idImageMM;
    public ?string $name;
    public ?int $width;
    public ?int $height;
    public ?int $depth;
    public ?object $imagePath;

}
