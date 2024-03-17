<?php

namespace App\Services;

use Orkester\Manager;
use App\Repositories\Genre;

class GenreService
{
    public static function listGenres()
    {
        $genre = new Genre();
        debug($genre->listAllGenres()->getResult());
        return $genre->listAllGenres()->getResult();
    }
}