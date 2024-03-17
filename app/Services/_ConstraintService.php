<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Repositories\ConstraintInstance;
use App\Repositories\ConstraintType;
use App\Repositories\EntityRelation;

class ConstraintService extends Controller
{
    static public function create(int $idConstraint, string $constraintTypeEntry, int $idConstrained, int $idConstrainedBy)
    {
        $ct = new ConstraintType();
        $ct->getByEntry($constraintTypeEntry);
        $ci = new ConstraintInstance();
        $ci->saveData([
            'idConstraintType' => $ct->idConstraintType,
            'idConstraint' => $idConstraint,
            'idConstrained' => $idConstrained,
            'idConstrainedBy' => $idConstrainedBy
        ]);
    }

    public static function delete(int $idConstraintInstance)
    {
        $ci = new ConstraintInstance($idConstraintInstance);
        $ci->delete();
    }

}
