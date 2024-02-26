<?php

namespace App\Data;

use App\Services\AppService;
use Spatie\LaravelData\Data;

class SearchLexiconData extends Data
{
    public function __construct(
        public ?string $lemma = '',
        public ?string $lexeme = '',
        public int|string $idLanguage = 2,
        public string $_token = '',
    )
    {
        $this->idLanguage = AppService::getCurrentIdLanguage();
        $this->_token = csrf_token();
    }
}
