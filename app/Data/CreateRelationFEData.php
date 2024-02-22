<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CreateRelationFEData extends Data
{
    public function __construct(
        public int $idEntityRelation,
        public int $idFrameElement,
        public int $idFrameElementRelated,
    )
    {
    }
}
