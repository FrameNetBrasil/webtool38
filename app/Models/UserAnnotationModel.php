<?php

namespace App\Models;

use Orkester\Persistence\Model;

class UserAnnotationModel extends Model
{
    public ?int $idUserAnnotation;
    public ?int $idUser;
    public ?int $idSentenceStart;
    public ?int $idSentenceEnd;
    public ?int $idDocument;
    public ?object $document;
    public ?object $sentenceStart;
    public ?object $sentenceEnd;
    public ?object $user;
}
