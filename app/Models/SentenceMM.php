<?php

namespace App\Models;

use Orkester\Persistence\Model;

class SentenceMM extends Model
{
    public ?int $idSentenceMM;
    public ?int $startTimestamp;
    public ?int $endTimestamp;
    public ?int $startTime;
    public ?int $endTime;
    public ?int $origin;
    public ?int $idDocumentMM;
    public ?int $idSentence;
    public ?int $idOriginMM;
    public ?int $idImageMM;
    public ?object $sentence;
    public ?object $imageMM;
    public ?object $documentMM;
    public ?array $objectSentenceMM;

}
