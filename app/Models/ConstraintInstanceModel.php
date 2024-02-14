<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Maestro\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class ConstraintInstanceModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('entityrelation');
        self::attribute('idConstraintInstance', field:'idEntityRelation', key: Key::PRIMARY);
        self::attribute('idConstraintType', field:'idRelationType', type: Type::INTEGER);
        self::attribute('idConstraint', field:'idEntity1', type: Type::INTEGER);
        self::attribute('idConstrained', field:'idEntity2',type: Type::INTEGER);
        self::attribute('idConstrainedBy', field:'idEntity3',type: Type::INTEGER);
        self::associationOne('constraintType', model: RelationTypeModel::class, key: 'idConstraintType:idRelationType');
        self::associationOne('entityConstraint', model: EntityModel::class, key: 'idConstraint:idEntity');
        self::associationOne('entityConstrained', model: EntityModel::class, key: 'idConstrained:idEntity');
        self::associationOne('entityConstrainedBy', model: EntityModel::class, key: 'idConstrainedBy:idEntity');
        self::associationOne('constrainedFE', model: FrameElementModel::class, key: 'idConstrained:idEntity');
    }

}
