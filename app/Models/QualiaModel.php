<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Maestro\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class QualiaModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        
        self::table('qualia');
        self::attribute('idQualia', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('info');
        self::attribute('infoInverse');
        self::attribute('idTypeInstance', key: Key::FOREIGN);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('idFrame', key: Key::FOREIGN);
        self::attribute('idFrameElement1', key: Key::FOREIGN);
        self::attribute('idFrameElement2', key: Key::FOREIGN);
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('typeInstance', model: TypeInstanceModel::class);
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationOne('frame', model: FrameModel::class, key: 'idFrame:idFrame');
        self::associationMany('frameElement1', model: FrameElementModel::class, keys: 'idFrameElement1:idFrameElement');
        self::associationMany('frameElement2', model: FrameElementModel::class, keys: 'idFrameElement2:idFrameElement');
    }

}
