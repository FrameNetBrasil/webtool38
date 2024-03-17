<?php

namespace App\Services;

use App\Repositories\Group;
use App\Repositories\Qualia;
use Orkester\Manager;
use App\Models\GroupModel;

class GroupService
{
    public static function listForSelect()
    {
        $group = new Group();
        $data = Manager::getData();
        $q = $data->q ?? '';
        return $group->listForSelect($q)->getResult();
    }

}
