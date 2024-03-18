<?php
namespace App\Models;

use Orkester\Persistence\Model;

class ViewFrameElement extends Model
{

    public ?int $idFrameElement;
    public ?string $entry;
    public ?string $typeEntry;
    public ?string $frameEntry;
    public ?int $frameIdEntity;
    public ?int $active;
    public ?int $idEntity;
    public ?int $idFrame;
    public ?int $idColor;
    public ?string $name;
    public ?string $description;
    public ?int $idLanguage;
    public ?array $entries;
    public ?object $frame;
    public ?object $color;
    public ?array $labels;

}

