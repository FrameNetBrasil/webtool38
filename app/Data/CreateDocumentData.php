<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CreateDocumentData extends Data
{
    public function __construct(
        public int $idCorpus,
        public ?string $nameEn = '',
        public string $_token = '',
    )
    {
        $this->_token = csrf_token();
    }
}
