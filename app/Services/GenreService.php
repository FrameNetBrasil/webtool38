<?php

namespace App\Services;

use Orkester\Manager;
use App\Repositories\Genre;

class GenreService
{
    public static function listGenres()
    {
        debug("allo");
        $genre = new Genre();
        debug($genre->listAll()->getResult());
        return $genre->listAll()->getResult();
    }
}