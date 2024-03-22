<?php

namespace App\Models;

use Orkester\Persistence\Model;

class DynamicSentenceMMModel extends Model
{
    public ?int $idDynamicSentenceMM;
    public ?int $startTime;
    public ?int $endTime;
    public ?int $origin;
    public ?int $idSentence;
    public ?int $idOriginMM;
    public ?object $sentence;
    public ?object $originMM;

}
