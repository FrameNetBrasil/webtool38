<?php

namespace App\Models;

use Orkester\Persistence\Model;

class Corpus extends Model
{

    public ?int $idCorpus;
    public ?string $entry;
    public ?int $active;
    public ?int $idEntity;
    public ?string $name;
    public ?string $description;
    public ?int $idLanguage;
    public ?array $entries;
    public ?array $documents;

}
