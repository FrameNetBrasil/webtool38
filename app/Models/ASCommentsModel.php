<?php

namespace App\Models;

use Orkester\Persistence\Model;

class ASCommentsModel extends Model
{

    public ?int $idASComments;
    public ?string $extraThematicFE;
    public ?string $extraThematicFEOther;
    public ?string $comment;
    public ?string $construction;
    public ?int $idAnnotationSet;
    public ?object $annotationSet;

}

