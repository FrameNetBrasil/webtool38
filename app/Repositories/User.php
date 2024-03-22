<?php

namespace App\Repositories;

use App\Services\AppService;
use Carbon\Carbon;
use Orkester\Persistence\Criteria\Criteria;
use Orkester\Persistence\Repository;
use Orkester\Persistence\PersistenceManager;
use App\Models\UserModel as UserModel;

class User extends Repository
{

    public static function getById(int $id): UserModel
    {
        $user = new UserModel;
        parent::getModelById($user, $id);
        $user->groups = static::retrieveAssociation($user, 'groups');
        foreach ($user->groups as $group) {
            $g = $group->name;
            $user->memberOf[$g] = $g;
        }
        return $user;
    }

//    public function delete()
//    {
//        $this->deleteAssociation('groups');
//        parent::delete();
//    }

    public static function listByFilter(object $filter): Criteria
    {
        $criteria = static::getCriteria()
            ->select('*')
            ->orderBy('login');
        return self::filter([
            ['idUser','=',$filter?->idUser ?? null],
            ['login','startswith',$filter?->login ?? null],
            ['passMD5','=',$filter?->passMD5 ?? null],
            ['name','startswith',$filter?->name ?? null],
            ['email','startswith',$filter?->email ?? null],
            ['status','=',$filter?->status ?? null],
        ], $criteria);
    }

    public static function create(UserModel $user): ?int
    {
        PersistenceManager::beginTransaction();
        try {
            $user->registerLogin();
            $idUser = $user->idUser = static::save($user);
            static::saveAssociation($user, 'groups');
            PersistenceManager::commit();
            return $idUser;
        } catch (\Exception $e) {
            PersistenceManager::rollback();
            return null;
        }
    }

//    public static function registerLogin()
//    {
//        $this->lastLogin = Carbon::now();
//        $this->save();
//    }


    /*
    public function getArrayGroups()
    {
        $aGroups = array();
        if (empty($this->groups)) {
            $this->retrieveAssociation('groups');
        }
        foreach ($this->groups as $group) {
            $g = $group->name;
            $aGroups[$g] = $g;
        }
        return $aGroups;
    }

    public function getRights()
    {
        $query = $this->getCriteria()
            ->select(['groups.access.transaction.name', 'max(groups.access.rights) as rights'])
            ->where("login", "=", $this->login)
            ->groupBy('groups.access.transaction.name')
            ->asQuery();
        return $query->chunkResult('name', 'rights', false);
    }

    public function weakPassword()
    {
        $weak = ($this->passMD5 == MD5('010101')) || ($this->passMD5 == MD5($this->login));
        return $weak;
    }

    public function resetPassword()
    {
        $this->newPassword(config('webtool.defaultPassword'));
    }

    public function newPassword($password)
    {
        $this->passMD5 = md5($password);
        $this->save();
    }

    public function validatePassword($password)
    {
        return ($this->passMD5 == md5($password));
    }

    public function validatePasswordMD5($challenge, $response)
    {
        $hash_pass = MD5(trim($this->login) . ':' . trim($this->passMD5) . ":" . $challenge);
        return ($hash_pass == $response);
    }

    public function getByLogin(string $login)
    {
        $criteria = $this->getCriteria()
            ->where("login", "=", $login);
        $this->retrieveFromCriteria($criteria);
    }

    public function listGroups()
    {
        $criteria = $this->getCriteria()
            ->select("groups.idGroup,groups.name")
            ->orderBy("groups.name");
        if ($this->idUser) {
            $criteria->where("idUser", "=", $this->idUser);
        }
        return $criteria;
    }

    public function getConfigData($attr)
    {
        $config = $this->config;
        if ($config == '') {
            $config = new \StdClass();
            $config->$attr = '';
        } else {
            $config = unserialize($config);
        }
        return $config->$attr ?? null;
    }

    public function setConfigData($attr, $value)
    {
        $config = $this->config;
        if ($config == '') {
            $config = (object)[
                $attr => ''
            ];
        } else {
            $config = unserialize($config);
        }
        $config->$attr = $value;
        $this->config = serialize($config);
        parent::save();
    }



    public function getAvaiableLevels()
    {
        $levels = [];
        $criteria = $this->getCriteria()
            ->select('idUser')
            ->where("idUser", "=", $this->idUser);
        $users = $criteria->asQuery()->getResult();
        foreach ($users as $row) {
            $idUser = $row['idUser'];
            $tempUser = new User($idUser);
            $level = $tempUser->getUserLevel();
            $levels[$level] = $idUser;
        }
        return $levels;
    }

    public function setUserLevel($userLevel)
    {
        $currentLevel = $this->getUserLevel();
        if ($currentLevel != $userLevel) {
            $newGroups = [];
            $g = new Group();
            $g->getByName($userLevel);
            $newGroups[] = $g;
            $this->groups = $newGroups;
            $this->saveAssociation('groups');
        }
    }

    public function getUsersOfLevel($level)
    {
        $criteria = $this->getCriteria()->select("idUser, login")
            ->where("groups.name = '{$level}'")
            ->orderBy("login");
        return $criteria->asQuery()->chunkResult('idUser', 'login');
    }

    public function getUserSupervisedByIdLU($idLU)
    {
        $criteria = $this->getCriteria()->select('idUser,config');
        $rows = $criteria->asQuery()->getResult();
        foreach ($rows as $row) {
            $config = unserialize($row['config']);
            $lus = $config->fnbrConstraintsLU;
            if ($lus) {
                foreach ($lus as $id) {
                    if ($idLU == $id) {
                        $userSupervised = new User($row['idUser']);
                        return $userSupervised;
                    }
                }
            }
        }
        return NULL;
    }



    public function isAdmin()
    {
        return in_array('ADMIN', $this->memberOf);
    }

    public function isMemberOf($group)
    {
        return in_array(strtoupper($group), $this->memberOf) || $this->isAdmin();
    }

    public function save(): ?int
    {
        if ($this->passMD5 == '') {
            $this->passMD5 = md5(config('webtool.defaultPassword'));
        }
        return parent::save();
    }

    public function addToGroup(int $idGroup)
    {
        $this->groups[$idGroup] = new Group($idGroup);
        $this->saveAssociation('groups');
    }

    public function deleteFromGroup(int $idGroup)
    {
        unset($this->groups[$idGroup]);
        $this->saveAssociation('groups');
    }
*/

}
