<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class StaticObjectSentenceMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {

        self::table('staticobjectsentencemm');
        self::attribute('idStaticObjectSentenceMM', key: Key::PRIMARY);
        self::attribute('name');
        self::attribute('startWord', type: Type::INTEGER);
        self::attribute('endWord', type: Type::INTEGER);
        self::attribute('idStaticSentenceMM', type: Type::INTEGER, key:Key::FOREIGN);
        self::attribute('idStaticObjectMM', type: Type::INTEGER, key:Key::FOREIGN);
        self::associationOne('staticSentenceMM', model: StaticSentenceMMModel::class, key: 'idStaticSentenceMM');
        self::associationOne('staticObjectMM', model: StaticObjectMMModel::class, key: 'idStaticObjectMM');
    }

}
