<?php

namespace App\Models;

use Orkester\Persistence\Model;

class Label extends Model
{

    public ?int $idLabel;
    public ?int $startChar;
    public ?int $endChar;
    public ?int $multi;
    public ?int $idLabelType;
    public ?int $idLayer;
    public ?int $idInstantiationType;
    public ?object $genericLabel;
    public ?object $frameElement;
    public ?object $constructionElement;
    public ?object $layer;
    public ?object $instantiationType;

}
