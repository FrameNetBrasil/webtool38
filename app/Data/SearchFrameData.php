<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class SearchFrameData extends Data
{
    public function __construct(
        public ?string $frame = '',
        public ?string $fe = '',
        public ?string $lu = '',
        public ?string $listBy = '',
        public ?string $idFramalDomain = '',
        public ?string $idFramalType = '',
        public string $_token = '',
    )
    {
        $this->_token = csrf_token();
    }
}
