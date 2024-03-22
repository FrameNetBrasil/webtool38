<?php

namespace App\Models;

use Orkester\Persistence\Model;

class ObjectSentenceMMModel extends Model
{
    public ?string $name;
    public ?int $idObjectSentenceMM;
    public ?int $startWord;
    public ?int $endWord;
    public ?int $idFrameElement;
    public ?int $idLU;
    public ?int $idLemma;
    public ?int $idSentenceMM;
    public ?int $idObjectMM;
    public ?object $sentenceMM;
    public ?object $objectMM;

}
