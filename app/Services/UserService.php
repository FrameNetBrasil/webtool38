<?php

namespace App\Services;

use App\Models\UserModel;
use App\Repositories\Base;
use Orkester\Manager;
use App\Repositories\User;



class UserService {

    public static function listByFilter($filter = '')
    {
        debug($filter);
        $user = new User();
        return $user->listByFilter($filter)->getResult();
    }

    public static function listGroupsForGrid(int $idUser)
    {
        $result = [];
        $user = new User($idUser);
        $groups = $user->listGroups()->getResult();
        return $groups;
    }
}

