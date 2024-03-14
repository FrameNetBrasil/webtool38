<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class StaticAnnotationMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {

        self::table('staticannotationmm');
        self::attribute('idStaticAnnotationMM', key: Key::PRIMARY);
        self::attribute('idFrameElement', type: Type::INTEGER);
        self::attribute('idLU', type: Type::INTEGER);
        self::attribute('idLemma', type: Type::INTEGER);
        self::attribute('idFrame', type: Type::INTEGER);
        self::attribute('idStaticObjectSentenceMM', type: Type::INTEGER, key:Key::FOREIGN);
        self::associationOne('staticObjectSentenceMM', model: StaticObjectSentenceMMModel::class, key: 'idStaticObjectSentenceMM');
        self::associationOne('frameElement', model: FrameElementModel::class, key: 'idFrameElement');
        self::associationOne('lu', model: LUModel::class, key: 'idLU');
        self::associationOne('lemma', model: LemmaModel::class, key: 'idLemma');
        self::associationOne('frame', model: FrameModel::class, key: 'idFrame');
    }

}
