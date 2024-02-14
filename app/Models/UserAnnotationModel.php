<?php

namespace App\Models;


use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Maestro\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class UserAnnotationModel extends Model
{
    public static function map(ClassMap $classMap): void
    {

        self::table('userannotation');
        self::attribute('idUserAnnotation', key: Key::FOREIGN);
        self::attribute('idUser', key: Key::FOREIGN);
        self::attribute('idSentenceStart', type: Type::INTEGER);
        self::attribute('idSentenceEnd', key: Key::FOREIGN);
        self::attribute('idDocument', key: Key::FOREIGN);
        self::associationOne('document', model: DocumentModel::class, key: 'idDocument');
        self::associationOne('sentenceStart', model: SentenceModel::class, key: 'idSentenceStart:idSentence');
        self::associationOne('sentenceEnd', model: SentenceModel::class, key: 'idSentenceEnd:idSentence');
        self::associationOne('user', model: UserModel::class, key: 'idUser:idUser');
    }


}
