<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Maestro\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class OriginMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('originmm');
        self::attribute('idOriginMM', key: Key::PRIMARY);
        self::attribute('origin');
    }

}