<?php

namespace Maestro\Persistence;

use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\PersistenceManager;

class Model extends \Orkester\Persistence\Model
{

    public static function map(ClassMap $classMap)
    {
        // TODO: Implement map() method.
    }

    public static function getCriteria(string $databaseName = null): Criteria {
        $classMap = PersistenceManager::getClassMap(static::class);
        $connection = PersistenceManager::getConnection($databaseName);
        $criteria = new Criteria($connection);
        return $criteria->setClassMap($classMap);
    }

}