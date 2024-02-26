<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CreateLemmaData extends Data
{
    public function __construct(
        public int $idLanguage,
        public int $idPOS,
        public string $name,
        public string $_token = '',
    )
    {
        $this->_token = csrf_token();
    }
}
