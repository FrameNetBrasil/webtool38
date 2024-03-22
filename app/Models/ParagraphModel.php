<?php
namespace App\Models;

use Orkester\Persistence\Model;

class ParagraphModel extends Model {

    public ?int $idParagraph;
    public ?int $documentOrder;
    public ?int $idDocument;
    public ?array $sentences;
    public ?object $document;

}