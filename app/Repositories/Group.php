<?php

namespace App\Repositories;

use App\Models\GroupModel;
use Orkester\Persistence\Repository;

class Group extends Repository
{

//    public ?int $idGroup;
//    public ?string $name;
//    public ?string $description;
//
//    public function __construct(int $id = null)
//    {
//        parent::__construct(GroupModel::class, $id);
//    }
//
//    public function getDescription()
//    {
//        return $this->name;
//    }

    public static function getByName($name)
    {
        $criteria = static::getCriteria()
            ->select('*');
        $criteria->where("upper(name)", "=", strtoupper($name));
        return static::retrieveFromCriteria($criteria);
    }

    public function listByFilter($filter = '')
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idGroup');
        if (isset($filter->idGroup)) {
            $criteria->where("idGroup LIKE '{$filter->idGroup}%'");
        }
        return $criteria;
    }

    public function listForSelect()
    {
        $criteria = $this->getCriteria()->select(['idGroup','name'])->orderBy('idGroup');
        return $criteria;
    }

    public function listUser()
    {
        $criteria = $this->getCriteria()->select('users.idUser, users.login')->orderBy('users.login');
        if ($this->idGroup) {
            $criteria->where("idGroup = {$this->idGroup}");
        }
        return $criteria;
    }
}

