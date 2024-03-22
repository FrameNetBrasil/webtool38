<?php

namespace App\Models;

use Orkester\Persistence\Model;

class ViewLUModel extends Model
{
    public ?int $idLU;
    public ?string $name;
    public ?string $senseDescription;
    public ?int $active;
    public ?int $importNum;
    public ?int $incorporatedFE;
    public ?int $idEntity;
    public ?int $idLemma;
    public ?int $idFrame;
    public ?string $frameEntry;
    public ?string $lemmaName;
    public ?int $idLanguage;
    public ?int $idPOS;
    public ?object $lemma;
    public ?object $frame;
    public ?object $language;
    public ?array $annotationSets;

}

