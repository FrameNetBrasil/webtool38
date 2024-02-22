<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class SearchRelationGroupData extends Data
{
    public function __construct(
        public ?string $relationGroup = '',
        public ?string $relationType = '',
        public string $_token = '',
    )
    {
        $this->_token = csrf_token();
    }
}
