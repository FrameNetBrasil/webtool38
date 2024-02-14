<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Maestro\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class AnnotationMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('annotationmm');
        self::attribute('idAnnotationMM', key: Key::PRIMARY);
        self::attribute('idObjectSentenceMM', key: Key::FOREIGN);
        self::attribute('idFrameElement', key: Key::FOREIGN);
        self::attribute('idFrame', key: Key::FOREIGN);
        self::associationOne('objectSentenceMM', model: ObjectSentenceMMModel::class, key: 'idObjectSentenceMM');
        self::associationOne('frameElement', model: FrameElementModel::class, key: 'idFrameElement');
        self::associationOne('frame', model: FrameModel::class, key: 'idFrame');
    }

}