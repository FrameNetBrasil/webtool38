<?php

namespace App\Models;

use Orkester\Persistence\Model;

class AnnotationMM extends Model
{
    public ?int $idAnnotationMM;
    public ?int $idObjectSentenceMM;
    public ?int $idFrameElement;
    public ?object $objectSentenceMM;
    public ?object $frameElement;
    public ?object $frame;

}
