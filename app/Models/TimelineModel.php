<?php

namespace App\Models;

use Orkester\Persistence\Model;
class TimelineModel extends Model
{
    public ?int $idTimeline;
    public ?string $tlDateTime;
    public ?string $author;
    public ?string $operation;
    public ?string $tableName;
    public ?int $idTable;
    public ?int $idUser;

}

