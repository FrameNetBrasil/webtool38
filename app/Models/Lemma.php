<?php

namespace App\Models;

use Orkester\Persistence\Model;

class Lemma extends Model
{
    public ?int $idLemma;
    public ?string $name;
    public ?string $version;
    public ?int $idLanguage;
    public ?int $idEntity;
    public ?object $entity;
    public ?object $pos;
    public ?object $udpos;
    public ?object $language;
    public ?array $lexemes;
    public ?array $lexemeEntries;
    public ?array $lus;

}
