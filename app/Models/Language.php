<?php

namespace App\Models;

use Orkester\Persistence\Model;

class Language extends Model {

    public ?int $idLanguage;
    public ?string $language;
    public ?string $description;

}
