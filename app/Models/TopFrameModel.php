<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class TopFrameModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('topframe');
        self::attribute('idTopFrame', key: Key::PRIMARY);
        self::attribute('frameBase');
        self::attribute('frameTop');
        self::attribute('frameCategory');
        self::attribute('score', type: Type::FLOAT);
        self::associationOne('frame', model: FrameModel::class, key: 'frameBase:entry');
    }

}
