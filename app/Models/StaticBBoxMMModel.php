<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Maestro\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class StaticBBoxMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('staticbboxmm');
        self::attribute('idStaticBBoxMM', key: Key::PRIMARY);
        self::attribute('x', type: Type::INTEGER);
        self::attribute('y', type: Type::INTEGER);
        self::attribute('width', type: Type::INTEGER);
        self::attribute('height', type: Type::INTEGER);
        self::attribute('idStaticObjectMM', type: Type::INTEGER, key: Key::FOREIGN);
        self::associationOne('staticObjectMM', model: StaticObjectMMModel::class, key: 'idObjectMM');
    }

    public static function listByStaticObjectMM(int $idStaticObjectMM): array
    {
        $filters = [];
        $filters[] = ['idStaticObjectMM','=', $idStaticObjectMM];
        return self::list($filters, ['*']);
    }

}