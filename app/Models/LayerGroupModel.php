<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class LayerGroupModel extends Model
{
    public static function map(ClassMap $classMap): void
    {

        self::table('layergroup');
        self::attribute('idLayerGroup', key: Key::PRIMARY);
        self::attribute('name');
        self::associationMany('layerType', model: LayerTypeModel::class, keys: 'idLayerGroup');
    }

}
