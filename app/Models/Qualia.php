<?php

namespace App\Models;

use Orkester\Persistence\Model;

class Qualia extends Model
{

    public ?int $idQualia;
    public ?string $entry;
    public ?string $info;
    public ?string $infoInverse;
    public ?int $idTypeInstance;
    public ?int $idEntity;
    public ?int $idFrame;
    public ?int $idFrameElement1;
    public ?int $idFrameElement2;
    public ?object $entity;
    public ?object $typeInstance;
    public ?array $entries;
    public ?object $frame;
    public ?array $frameElement1;
    public ?array $frameElement2;
}
