<?php

namespace App\Models;

use Orkester\Persistence\Model;

class Lexeme extends Model {

    public ?int $idLexeme;
    public ?string $name;
    public ?int $idLanguage;
    public ?object $entity;
    public ?object $pos;
    public ?object $udpos;
    public ?object $language;
    public ?array $lemmas;
    public ?array $lexemeEntries;
    public ?array $wordforms;

}

