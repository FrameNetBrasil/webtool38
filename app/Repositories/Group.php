<?php

namespace App\Repositories;

use Orkester\Persistence\Criteria\Criteria;
use Orkester\Persistence\Repository;

class Group extends Repository
{

    public static function listByFilter(?object $filter = null): Criteria
    {
        $criteria = static::getCriteria()
            ->select('*')
            ->orderBy('idGroup');
        return self::filter([
            ['idGroup','=',$filter?->idGroup ?? null],
        ], $criteria);
    }
    public static function getByName($name)
    {
        $criteria = static::getCriteria()
            ->select('*');
        $criteria->where("upper(name)", "=", strtoupper($name));
        return static::retrieveFromCriteria($criteria);
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

