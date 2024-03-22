<?php

namespace App\Models;

use Orkester\Persistence\Model;

class AccessModel extends Model {

    public ?int $idAccess;
    public ?int $rights;
    public ?int $idGroup;
    public ?int $idTransaction;
    public ?object $group;
//    public ?object $transaction;
}
