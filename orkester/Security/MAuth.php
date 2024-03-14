<?php

namespace Orkester\Security;

//use Maestro\Persistence\Repository;

use App\Repositories\User;

class MAuth
{
    private static ?User $login = null;

    public static function init(): void
    {
        self::$login = session('sessionLogin') ?? null;
    }

    public static function isLogged(): bool
    {
        return !is_null(self::$login);
    }

    public static function getLogin(): ?User
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
    /*
    private $login;  // objeto Login
    public $idUser; // iduser do usuario corrente

    public function __construct()
    {
        $this->login = session('sessionLogin') ?? null;
    }

    public function setLogin($login = false)
    {
        $this->login = $login;
        $this->updateSessionLogin();
        $this->idUser = ($this->login instanceof MLogin ? $this->login->getIdUser() : NULL);
    }

    public function setLoginLogUserId($userId)
    {
        $_SESSION['loginLogUserId'] = $userId;
        // ddump("setLoginLogUserId " . $userId);
    }

    public function getLoginLogUserId()
    {
        // ddump("getLoginLogUserId " . $_SESSION['loginLogUserId']);
        return $_SESSION['loginLogUserId'];
    }

    public function setLoginLog($login)
    {
        $_SESSION['loginLog'] = $login;
    }

    public function getLoginLog()
    {
        return $_SESSION['loginLog'];
    }


    public function getLogin()
    {
        if ($this->login instanceof MLogin) {
            return $this->login;
        } else {
            $login = session('sessionLogin') ?? '';
            if ($login instanceof MLogin) {
                $this->login = $login;
                return $login;
            }
        }
        return null;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function checkLogin()
    {
        ddump('[LOGIN] Running CheckLogin');

// if not checking logins, we are done
        if ((!MUtil::getBooleanValue(Manager::$conf['login']['check']))) {
            ddump('[LOGIN] I am not checking login today...');
            return true;
        }

// we have a session login?
        $login = session('__sessionLogin');
        if ($login instanceof MLogin) {
            if ($login->getLogin()) {
                ddump('[LOGIN] Using session login: ' . $login->getLogin());
                $this->setLogin($login);
                return true;
            }
        }
// if we have already a login, assume it is valid and return
        if ($this->login instanceof MLogin) {
            ddump('[LOGIN] Using existing login:' . $this->login->getLogin());
            return true;
        }
        ddump('[LOGIN] No Login but Login required!');
        return false;
    }

    public function authenticate($userId, $challenge, $response)
    {
        return false;
    }

    public function isLogged()
    {
        if ($this->login instanceof MLogin) {
            return ($this->login->getLogin() != NULL);
        } else {
            $login = session('sessionLogin') ?? '';
            if ($login instanceof MLogin) {
                return ($login->getLogin() != NULL);
            }
        }
        return false;
    }

    public function logout($forced = '')
    {
        $this->setLogin(NULL);
        //Manager::getSession()->destroy();
    }

    private function updateSessionLogin()
    {
        ddump('session login');
        session(['sessionLogin' => $this->login]);
    }

    public function updateSessionLoginIfIsUserLogged(Repository $user)
    {
        if ($this->login->isUserLogged($user)) {
            $this->login->setUser($user);
            $this->updateSessionLogin();
        }
    }
    */

}
