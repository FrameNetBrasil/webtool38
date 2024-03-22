<?php
namespace App\Models;

use Orkester\Persistence\Model;

class ObjectMMModel extends Model {

    public ?int $idObjectMM;
    public ?string $name;
    public ?int $startFrame;
    public ?int $endFrame;
    public ?int $startTime;
    public ?int $endTime;
    public ?string $status;
    public ?int $origin;
    public ?int $idDocumentMM;
    public ?int $idFrameElement;
    public ?int $idFlickr30k;
    public ?int $idImageMM;
    public ?int $idLemma;
    public ?int $idLU;
    public ?object $documentMM;
    public ?object $frameElement;
    public ?object $lu;
    public ?object $lemma;

}
