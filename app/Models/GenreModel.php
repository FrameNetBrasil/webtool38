<?php


namespace App\Models;

use Orkester\Persistence\Model;

class GenreModel extends Model {
    public ?int $idGenre;
    public ?string $entry;
    public ?int $idGenreType;
    public ?int $idEntity;

}
