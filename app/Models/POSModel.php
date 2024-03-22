<?php

namespace App\Models;

use Orkester\Persistence\Model;

class POSModel extends Model
{

    public ?int $idPOS;
    public ?string $POS;
    public ?string $entry;
    public ?int $idEntity;
    public ?object $entity;
    public ?array $entries;

}
