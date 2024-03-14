<?php

namespace App\Services;

use App\Repositories\User;
use Orkester\Security\MAuth;
use Orkester\Security\MLogin;

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
        $user = new User();
        $result = $user->listByFilter($userData)->asQuery()->getResult();
        if (count($result) == 0) {
            $user->createUser($userData);
            return 'new';
        } else {
            $user->getById($result[0]['idUser']);
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
        debug($userData);
        $user = new User();
        $result = $user->listByFilter($userData)->asQuery()->getResult();
        if (count($result) == 0) {
            $user->createUser($userData);
            return 'new';
        } else {
            $user->getById($result[0]['idUser']);
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


}
