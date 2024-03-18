<?php

namespace App\Models;

use Orkester\Persistence\Model;

class StaticObjectSentenceMM extends Model
{
    public ?int $idStaticObjectSentenceMM;
    public ?string $name;
    public ?string $startWord;
    public ?string $endWord;
    public ?int $idStaticSentenceMM;
    public ?int $idStaticObjectMM;
    public ?object $staticSentenceMM;
    public ?object $staticObjectMM;

}
