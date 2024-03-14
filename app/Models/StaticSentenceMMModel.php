<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class StaticSentenceMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {

        self::table('staticsentencemm');
        self::attribute('idStaticSentenceMM', key: Key::PRIMARY);
        self::attribute('idFlickr30k', type: Type::INTEGER);
        self::attribute('idDocument', type: Type::INTEGER, key: Key::FOREIGN);
        self::attribute('idSentence', type: Type::INTEGER, key: Key::FOREIGN);
        self::attribute('idImageMM', type: Type::INTEGER, key: Key::FOREIGN);
        self::associationOne('sentence', model: SentenceModel::class, key: 'idSentence:idSentence');
        self::associationOne('imageMM', model: ImageMMModel::class, key: 'idImageMM:idImageMM');
        self::associationOne('document', model: DocumentModel::class, key: 'idDocument');
        self::associationMany('staticObjectSentenceMM', model: StaticObjectSentenceMMModel::class, keys: 'idStaticSentenceMM:idStaticSentenceMM');
    }

}
