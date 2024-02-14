<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Maestro\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class CorpusModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        
        self::table('corpus');
        self::attribute('idCorpus', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('active');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationMany('documents', model: DocumentModel::class, keys: 'idCorpus');
    }

}
