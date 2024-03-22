<?php

namespace App\Models;

use Orkester\Persistence\Model;

class DocumentMMModel extends Model
{

    public ?int $idDocumentMM;
    public ?string $name;
    public ?string $title;
    public ?string $originalFile;
    public ?string $sha1Name;
    public ?string $audioPath;
    public ?string $videoPath;
    public ?int $videoWidth;
    public ?int $videoHeight;
    public ?string $alignPath;
    public ?int $flickr30k;
    public ?string $url;
    public ?int $idDocument;
    public ?int $idLanguage;
    public ?object $document;
    public ?array $sentenceMM;

}
