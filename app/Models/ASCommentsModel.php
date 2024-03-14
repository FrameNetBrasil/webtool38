<?php

namespace App\Models;

use Orkester\Persistence\Model;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;

class ASCommentsModel extends Model
{
    public static function map(ClassMap $classMap): void
    {

        self::table('ascomments');
        self::attribute('idASComments', key: Key::PRIMARY);
        self::attribute('extraThematicFE');
        self::attribute('extraThematicFEOther');
        self::attribute('comment');
        self::attribute('construction');
        self::attribute('idAnnotationSet', key: Key::FOREIGN);
        self::associationOne('annotationSet', model: AnnotationSetModel::class, key: 'idAnnotationSet');
    }

}


