<?php

namespace App\Repositories;

use App\Models\EntryModel;
use App\Models\FrameElementModel;
use App\Models\LUModel;
use App\Models\RelationModel;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;

class Mapping extends \Orkester\Persistence\Mapping
{
    public static function ViewFrame(ClassMap $classMap): void
    {
        $classMap->table('view_frame')
        ->attribute('idFrame', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('active', type: Type::INTEGER)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationMany('entries', model: 'Entry', keys:'idEntity:idEntity')
        ->associationMany('lus', model: 'LU', keys: 'idFrame')
        //->associationMany('fes', model: 'FrameElement', keys: 'idFrame')
        ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity')
        //->associationMany('relations', model: 'ViewRelation', keys: 'idEntity:idEntity1')
        //->associationMany('inverseRelations', model: 'ViewRelation', keys: 'idEntity:idEntity2');
        ;
    }

    public static function Entry(ClassMap $classMap): void
    {

        $classMap->table('entry')
        ->attribute('idEntry', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('name')
        ->attribute('description')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->associationOne('language', model: 'Language')
        ->associationOne('entity', model: 'Entity', key: 'idEntity');
    }

    public static function Language(ClassMap $classMap): void
    {
        $classMap->table('language')
        ->attribute('idLanguage', key: Key::PRIMARY)
        ->attribute('language')
        ->attribute('description');
    }

    public static function Entity(ClassMap $classMap): void
    {
        $classMap->table('entity')
        ->attribute('idEntity', key: Key::PRIMARY)
        ->attribute('type')
        ->attribute('alias');
    }

    public static function LU(ClassMap $classMap): void
    {

        $classMap->table('lu')
        ->attribute('idLU', key: Key::PRIMARY)
        ->attribute('name')
        ->attribute('senseDescription')
        ->attribute('active', type: Type::INTEGER)
        ->attribute('importNum', type: Type::INTEGER)
        ->attribute('idFrame', key: Key::FOREIGN)
        ->attribute('incorporatedFE', key: Key::FOREIGN)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('idLanguage',reference: 'lemma.idLanguage')
        ->associationOne('entity', model: 'Entity');
//        ->associationOne('lemma', model: 'Lemma')
//        ->associationOne('frame', model: 'Frame', key: 'idFrame')
//        ->associationOne('frameElement', model: 'FrameElement', key: 'incorporatedFE:idFrameElement');
    }
}
