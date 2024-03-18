<?php

namespace App\Models;

use Orkester\Persistence\Model;

class Sentence extends Model
{
    public ?int $idSentence;
    public ?string $text;
    public ?int $paragraphOrder;
    public ?int $idParagraph;
    public ?int $idLanguage;
    public ?int $idDocument;
    public ?object $paragraph;
    public ?object $document;
    public ?object $language;
    public ?array $sentenceMM;
    public ?array $documents;

}
