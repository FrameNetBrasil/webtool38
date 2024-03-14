<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class DynamicBBoxMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {

        self::table('dynamicbboxmm');
        self::attribute('idDynamicBBoxMM', key: Key::PRIMARY);
        self::attribute('frameNumber', type: Type::INTEGER);
        self::attribute('frameTime', type: Type::FLOAT);
        self::attribute('x', type: Type::INTEGER);
        self::attribute('y', type: Type::INTEGER);
        self::attribute('width', type: Type::INTEGER);
        self::attribute('height', type: Type::INTEGER);
        self::attribute('blocked', type: Type::INTEGER);
        self::attribute('idDynamicObjectMM', type: Type::INTEGER, key: Key::FOREIGN);
    }

}
