<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class RelationData extends Data
{
    public function __construct(
        public int $idRelationType,
        public int  $idEntity1,
        public int  $idEntity2,
        public ?int $idEntity3 = null,
        public ?int $idEntityRelation = null,
        public ?int $idRelation = null,
    )
    {
    }
}
