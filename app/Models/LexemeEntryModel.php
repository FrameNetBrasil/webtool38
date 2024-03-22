<?php

namespace App\Models;

use Orkester\Persistence\Model;

class LexemeEntryModel extends Model
{

    public ?int $idLexemeEntry;
    public ?int $lexemeOrder;
    public ?int $breakBefore;
    public ?int $headWord;
    public ?int $idLexeme;
    public ?int $idLemma;
    public ?object $lemma;
    public ?object $lexeme;
    public ?object $wordForm;

}
