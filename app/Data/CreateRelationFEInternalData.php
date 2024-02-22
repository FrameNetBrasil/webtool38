<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CreateRelationFEInternalData extends Data
{
    public int $idRelationType;
    public function __construct(
        public object $idFrameElementRelated,
        public string $relationType,
    )
    {
        $this->idRelationType = (int)substr($this->relationType, 1);
    }
}
