<?php

namespace App\Services;

use App\Models\UserModel;
use App\Repositories\Base;
use Orkester\Manager;
use App\Repositories\User;


class UserService
{

    public static function listByFilter($filter = '')
    {
        debug($filter);
        $user = new User();
        return $user->listByFilter($filter)->getResult();
    }

    public static function listForTree()
    {
        $data = Manager::getData();
        $result = [];
        $filter = $data;
        $user = new User();
        $users = $user->listByFilter($filter)->asQuery()->getResult();
        foreach ($users as $row) {
            $node = [];
            $node['id'] = $row['idUser'];
            $node['idUser'] = $row['idUser'];
            $node['login'] = $row['login'];
            $node['email'] = $row['email'];
            $node['status'] = $row['status'];
            $node['lastLogin'] = $row['lastLogin'];
            $node['state'] = 'open';
            $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-user';
            $node['children'] = null;
            $result[] = $node;
        }
        return $result;
    }


    public static function listGroupsForGrid(int $idUser)
    {
        $result = [];
        $user = new User($idUser);
        $groups = $user->listGroups()->getResult();
        return $groups;
    }
}

