<?php

namespace App\Data;

use App\Services\AppService;
use Spatie\LaravelData\Data;

class SearchLexemeData extends Data
{
    public function __construct(
        public ?string $lexeme = '',
        public int|string $idLanguage = 2,
        public string $_token = '',
    )
    {
        $this->idLanguage = AppService::getCurrentIdLanguage();
        $this->_token = csrf_token();
    }
}
