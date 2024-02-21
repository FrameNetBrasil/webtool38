<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CreateRelationFrameData extends Data
{
    public function __construct(
        public int $idFrame,
        public string $relationType,
        public int $idFrameRelated,
        public ?int $idRelationType,
        public ?string $direction
    )
    {
        $this->direction = $this->relationType[0];
        $this->idRelationType = (int)(substr($this->relationType, 1));
    }
}
