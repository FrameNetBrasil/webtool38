<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class DynamicSentenceMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {

        self::table('dynamicsentencemm');
        self::attribute('idDynamicSentenceMM', key: Key::PRIMARY);
        self::attribute('startTime', type: Type::FLOAT);
        self::attribute('endTime', type: Type::FLOAT);
        self::attribute('origin', type: Type::INTEGER);
        self::attribute('idSentence', type: Type::INTEGER, key: Key::FOREIGN);
        self::attribute('idOriginMM', type: Type::INTEGER, key: Key::FOREIGN);
        self::associationOne('sentence', model: SentenceModel::class, key: 'idSentence:idSentence');
        self::associationOne('originMM', model: OriginMMModel::class, key: 'idOriginMM');
    }

}
