<?php

namespace App\Models;

use Orkester\Persistence\Model;

class LUModel extends Model
{
    //private $idFrame;
    public ?int $idLU;
    public ?string $name;
    public ?string $senseDescription;
    public ?int $active;
    public ?int $importNum;
    public ?int $idEntity;
    public ?int $idFrame;
    public ?int $idLanguage;
    public ?object $entity;
    public ?object $lemma;
    public ?object $frame;

}
