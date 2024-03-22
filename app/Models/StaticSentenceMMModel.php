<?php

namespace App\Models;

use Orkester\Persistence\Model;

class StaticSentenceMMModel extends Model
{
    public ?int $idStaticSentenceMM;
    public ?int $idFlickr30k;
    public ?int $idDocument;
    public ?int $idSentence;
    public ?int $idImageMM;
    public ?object $sentence;
    public ?object $imageMM;
    public ?object $document;
    public ?array $staticObjectSentenceMM;

}
