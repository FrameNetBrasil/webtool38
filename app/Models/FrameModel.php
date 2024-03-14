<?php

namespace App\Models;

use App\Services\AppService;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\PersistenceManager;

class FrameModel extends Model
{
    public static function map(ClassMap $classMap): void
    {

        self::table('frame');
        self::attribute('idFrame', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('active', type: Type::INTEGER);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationOne('entity', model: EntityModel::class);
        self::associationMany('lus', model: LUModel::class, keys: 'idFrame');
        self::associationMany('fes', model: FrameElementModel::class, keys: 'idFrame');
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationMany('relations', model: RelationModel::class, keys: 'idEntity:idEntity1');
        self::associationMany('inverseRelations', model: RelationModel::class, keys: 'idEntity:idEntity2');
    }

}
