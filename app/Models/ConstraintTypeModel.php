<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class ConstraintTypeModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('relationtype');
        self::attribute('idConstraintType', field:'idRelationType', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('prefix');
        self::attribute('idTypeInstance', key: Key::FOREIGN);
        self::attribute('idRelationGroup', key: Key::FOREIGN);
    }

}
