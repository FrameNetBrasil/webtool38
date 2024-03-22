<?php

namespace App\Models;

use Orkester\Persistence\Model;

class WordFormModel extends Model
{
    public ?int $idWordForm;
    public ?string $form;
    public ?string $md5;
    public ?object $entity;
    public ?object $lexeme;

}

