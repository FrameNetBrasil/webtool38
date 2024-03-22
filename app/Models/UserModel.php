<?php

namespace App\Models;

use App\Services\AppService;
use Carbon\Carbon;
use Orkester\Persistence\Model;
use App\Repositories\Group as GroupRepository;
use App\Repositories\User as UserRepository;

class UserModel extends Model
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

    public static function from(object $data): UserModel
    {
        $model = parent::from($data);
        $model->login ??= $data->email;
        $model->active ??= 1;
        $model->status ??= '0';
        $model->idLanguage ??= AppService::getCurrentIdLanguage();
        $model->groups ??= $data->groups;
        return $model;
    }

    public function registerLogin(): void
    {
        $this->lastLogin = Carbon::now();
        $this->idUser = $this->save();
    }
    public function getUserLevel()
    {
        $userLevel = '';
        $levels = AppService::userLevel();
        foreach($this->groups as $group) {
            foreach ($levels as $level) {
                if ($group->name == $level) {
                    $userLevel = $level;
                    break 2;
                }
            }
        }
        return $userLevel;
    }

    public function isAdmin()
    {
        return in_array('ADMIN', $this->memberOf);
    }

    public function isMemberOf($group)
    {
        return in_array(strtoupper($group), $this->memberOf) || $this->isAdmin();
    }

}
