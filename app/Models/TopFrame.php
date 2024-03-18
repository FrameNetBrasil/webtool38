<?php

namespace App\Models;

use Orkester\Persistence\Model;

class TopFrame extends Model
{
    public ?int $idTopFrame;
    public ?string $frameBase;
    public ?string $frameTop;
    public ?string $frameCategory;
    public ?float $score;
    public ?object $frame;

}

