<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class SearchDynamicModeData extends Data
{
    public function __construct(
        public ?string $corpus = '',
        public ?string $document = '',
        public string $_token = '',
    )
    {
        $this->_token = csrf_token();
    }
}
