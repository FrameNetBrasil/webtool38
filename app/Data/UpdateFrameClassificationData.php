<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class UpdateFrameClassificationData extends Data
{
    public function __construct(
        public array $framalData,
    )
    {
    }
}
