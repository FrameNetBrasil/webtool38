<?php

namespace App\Repositories;

use App\Models\UserModel;
use App\Services\AppService;
use Carbon\Carbon;
use Maestro\Persistence\Repository;
use Orkester\Persistence\PersistenceManager;

class User extends Repository
{

    public ?int $idUser;
    public ?string $login;
    public ?string $passMD5;
    public ?string $config;
    public ?string $name;
    public ?string $email;
    public ?string $status;
    public ?int $active;
    public ?string $auth0IdUser;
    public ?string $auth0CreatedAt;
    public ?string $lastLogin;
    public ?int $idLanguage;
    public ?array $groups;
    public ?array $memberOf;

    public function __construct(int $id = null)
    {
        parent::__construct(UserModel::class, $id);
    }

    public function getById(int $id): void
    {
        parent::getById($id);
        $this->retrieveAssociation('groups');
        foreach ($this->groups as $group) {
            $g = $group->name;
            $this->memberOf[$g] = $g;
        }
    }

    public function getDescription()
    {
        return $this->login;
    }

    public function delete()
    {
        $this->deleteAssociation('groups');
        parent::delete();
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()
            ->select('*')
            ->orderBy('login');
        if ($filter->idUser ?? false) {
            $criteria->where("idUser = {$filter->idUser}");
        }
        if ($filter->login ?? false) {
            $criteria->where("login LIKE '{$filter->login}%'");
        }
        if ($filter->passMD5 ?? false) {
            $criteria->where("passMD5 = '{$filter->passMD5}'");
        }
        if ($filter->name ?? false) {
            $criteria->where("name LIKE '{$filter->name}%'");
        }
        if (($filter->email ?? '') != '') {
            $criteria->where("email LIKE '{$filter->email}%'");
        }
        if (($filter->status ?? '') != '') {
            $criteria->where("status = '{$filter->status}'");
        }
        return $criteria;
    }

//    public function listForGrid($filter)
//    {
//        $levels = array_keys(Base::userLevel());
//        $constraintsLU = _M("Constraints_LU");
//        $preferences = _M("Preferences");
//        $criteria = $this->getCriteria()->select("*, idUser as resetPassword, name, email, groups.name as level, " .
//            "IF((groups.name = 'BEGINNER') or (groups.name = 'JUNIOR') or (groups.name = 'SENIOR'), '{$constraintsLU}','') as constraints, '{$preferences}' as preferences")->orderBy('login');
//        if ($filter->idUser) {
//            $criteria->where("idUser = {$filter->idUser}");
//        }
//        if ($filter->login) {
//            $criteria->where("login LIKE '{$filter->login}%'");
//        }
//        if ($filter->name) {
//            $criteria->where("name LIKE '{$filter->name}%'");
//        }
//        if ($filter->level) {
//            $criteria->where("upper(groups.name) LIKE upper('{$filter->level}%')");
//        }
//        $criteria->where('upper(groups.name)', 'IN', $levels);
//        return $criteria;
//    }

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

    public function getUserLevel()
    {
        $userLevel = '';
        $levels = Base::userLevel();
        $groups = $this->getArrayGroups();
        foreach ($levels as $level => $levelName) {
            if (isset($groups[$level])) {
                $userLevel = $level;
            }
        }
        return $userLevel;
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

    public function createUser($userData)
    {
        PersistenceManager::beginTransaction();
        try {
            //$this->setIdPerson(null);
            $this->login = $userData->email;
            $this->active = 1;
            $this->status = '0';
            $this->idLanguage = AppService::getCurrentIdLanguage();
            $group = new Group();
            $group->getByName('BEGINNER');
            $this->setData($userData);
            $this->registerLogin();
            $this->groups = [$group];
            $this->saveAssociation('groups');
            PersistenceManager::commit();
        } catch (\Exception $e) {
            ddump($e->getMessage());
            PersistenceManager::rollback();
        }
    }

    public function registerLogin()
    {
        $this->lastLogin = Carbon::now();
        $this->save();
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


}
