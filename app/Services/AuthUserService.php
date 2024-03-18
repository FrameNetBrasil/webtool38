<?php

namespace App\Services;

use App\Repositories\User;
use App\Models\User as UserModel;

class AuthUserService
{
    public function auth0Login($userInfo)
    {
        $userData = (object)[
            'auth0IdUser' => $userInfo['user_id'],
            'email' => $userInfo['email'],
            'auth0CreatedAt' => $userInfo['created_at'],
            'name' => $userInfo['name'],
            'nick' => $userInfo['nickname']
        ];
        debug($userData);
        $result = User::listByFilter($userData)->asQuery()->getResult();
        if (count($result) == 0) {
            $user = UserModel::from($userData);
            User::create($user);
            $user->registerLogin();
            return 'new';
        } else {
            $user = User::getById($result[0]['idUser']);
            if ($user->status == '0') {
                return 'pending';
            } else {
                $user->registerLogin();
                $idLanguage = $user->getConfigData('fnbrIdLanguage');
                if ($idLanguage == '') {
                    $idLanguage = 1;
                    $user->setConfigData('fnbrIdLanguage', $idLanguage);
                }
                session(['sessionLogin' => $user]);
                session(['idLanguage' => $idLanguage]);
                session(['userLevel' => $user->getUserLevel()]);
                debug("[LOGIN] Authenticated {$user->login}");
                return 'logged';
            }
        }
    }

    public function md5Login($userInfo)
    {
        $userData = (object)[
            'login' => $userInfo->login,
            'passMD5' => $userInfo->password,
        ];
        $result = User::listByFilter($userData)->getResult();
        if (count($result) == 0) {
            $user = UserModel::from($userData);
            User::create($user);
            $user->registerLogin();
            return 'new';
        } else {
            $user = User::getById($result[0]['idUser']);
            if ($user->status == '0') {
                return 'pending';
            } else {
                $user->registerLogin();
                $idLanguage = $user->idLanguage;
                if ($idLanguage == '') {
                    $idLanguage = config('webtool.defaultIdLanguage');
                }
                session(['sessionLogin' => $user]);
                session(['idLanguage' => $idLanguage]);
                session(['userLevel' => $user->getUserLevel()]);
                debug("[LOGIN] Authenticated {$user->login}");
                return 'logged';
            }
        }
    }


}
