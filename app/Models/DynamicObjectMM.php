<?php

namespace App\Models;

use Orkester\Persistence\Model;

class DynamicObjectMM extends Model
{
    public ?int $idDynamicObjectMM;
    public ?string $name;
    public ?int $startFrame;
    public ?int $endFrame;
    public ?float $startTime;
    public ?float $endTime;
    public ?int $status;
    public ?int $origin;
    public ?int $idDocument;
    public ?int $idFrameElement;
    public ?int $idLemma;
    public ?int $idLU;
    public ?object $document;
    public ?object $frameElement;
    public ?object $lu;
    public ?object $lemma;

}
