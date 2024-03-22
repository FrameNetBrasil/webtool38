<?php

namespace Orkester\Security;

//use Maestro\Persistence\Repository;

use App\Models\UserModel;

class MAuth
{
    private static ?UserModel $login = null;

    public static function init(): void
    {
        self::$login = session('sessionLogin') ?? null;
    }

    public static function isLogged(): bool
    {
        return !is_null(self::$login);
    }

    public static function getLogin(): ?UserModel
    {
        return self::$login;
    }

    public static function checkAccess(string $group): bool
    {
        if ($group == '') {
            return true;
        } else {
            $result = false;
            if (!is_null(self::$login)) {
                $user = self::$login;
                $result = $user->isMemberOf($group);
            }
            return $result;
        }
    }
    public static function logout(): void
    {
        session(['sessionLogin' => null]);
        self::$login = null;
    }
}
