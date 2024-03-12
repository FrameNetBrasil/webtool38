<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CreateFEData extends Data
{
    public function __construct(
        public ?string $nameEn,
        public ?int $idFrame,
        public ?string $coreType,
        public ?int $idColor,
        public string $_token = '',
    )
    {
        $this->_token = csrf_token();
    }
}
