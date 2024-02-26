<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CreateCorpusData extends Data
{
    public function __construct(
        public ?string $nameEn = '',
        public string $_token = '',
    )
    {
        $this->_token = csrf_token();
    }
}
