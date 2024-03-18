<?php

namespace App\Models;

use Orkester\Persistence\Model;

class StaticAnnotationMM extends Model
{
    public ?int $idStaticAnnotationMM;
    public ?int $idFrameElement;
    public ?int $idLU;
    public ?int $idLemma;
    public ?int $idFrame;
    public ?int $idStaticObjectSentenceMM;
    public ?object $staticObjectSentenceMM;
    public ?object $frameElement;
    public ?object $lu;
    public ?object $lemma;
    public ?object $frame;

}
