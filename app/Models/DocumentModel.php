<?php

namespace App\Models;

use Orkester\Persistence\Model;

class DocumentModel extends Model
{
    public ?int $idDocument;
    public ?string $entry;
    public ?int $active;
    public ?int $idEntity;
    public ?int $idCorpus;
    public ?string $author;
    public ?string $name;
    public ?string $description;
    public ?int $idLanguage;
    public ?array $entries;
    public ?object $corpus;
    public ?array $paragraphs;
    public ?array $sentences;

}
