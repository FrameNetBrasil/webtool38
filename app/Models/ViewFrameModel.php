<?php
namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Maestro\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;


class ViewFrameModel extends Model {

    public static function map(ClassMap $classMap): void
    {
        self::table('view_frame');
        self::attribute('idFrame', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('active', type: Type::INTEGER);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationMany('entries', model: EntryModel::class, keys:'idEntity:idEntity');
        self::associationMany('lus', model: LUModel::class, keys: 'idFrame');
        self::associationMany('fes', model: FrameElementModel::class, keys: 'idFrame');
    }

}
