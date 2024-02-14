<?php

namespace App\Modules\Index\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AppService;
use Auth0\SDK\Auth0;
use App\Repositories\Base;
use Maestro\Manager;

class MainController extends Controller
{

    public function init()
    {
        Manager::checkLogin(false);
    }

    public function main()
    {
        if (Manager::isLogged()) {
            return $this->render('formMain');
        } else {
            if (config('webtool.login.handler') == 'auth0') {
                return $this->render('auth0Login');
            } else {
                $this->data->challenge = uniqid(rand());
                session(['challenge', $this->data->challenge]);
                return $this->render('formLogin');
            }
        }
    }

    public function userdata()
    {
        return $this->render('userdata');
    }

    public function menu()
    {
        return $this->render('menu');
    }


    public function formMain()
    {
        return $this->render();
    }

    private function getAuth0()
    {
        $this->data->domain = env('AUTH0_DOMAIN');
        $this->data->client_id = env('AUTH0_CLIENT_ID');
        $this->data->client_secret = env('AUTH0_CLIENT_SECRET');
        $this->data->cookie_secret = env('AUTH0_COOKIE_SECRET');
        $this->data->redirect_uri = env('AUTH0_CALLBACK_URL');
        $this->data->base_url = env('AUTH0_BASE_URL');

        $auth0 = new Auth0([
            'domain' => $this->data->domain,
            'clientId' => $this->data->client_id,
            'clientSecret' => $this->data->client_secret,
            'cookieSecret' => $this->data->client_secret,
            'redirect_uri' => $this->data->redirect_uri,
            'tokenAlgorithm' => 'HS256'
        ]);
        return $auth0;
    }

    public function logout()
    {
        Manager::getAuth()->logout();
        $auth0 = $this->getAuth0();
        $auth0->logout('/');
        $this->redirect("/");
    }

    public function login()
    {
        $auth0 = $this->getAuth0();
        $auth0->clear();
        header("Location: " . $auth0->login($this->data->redirect_uri));
        exit;
    }

    public function auth0Callback()
    {
        try {
            $auth0 = $this->getAuth0();
            $auth0->exchange($this->data->redirect_uri);

            $userInfo = $auth0->getUser();
            $user = Manager::getAppService('AuthUser');
            $status = $user->auth0Login($userInfo);

            if ($status == 'new') {
                $this->notify('info', 'User registered. Please, login again.');
            } elseif ($status == 'pending') {
                $this->notify('info', 'User already registered, but waiting for Administrator approval.');
            } elseif ($status == 'logged') {
                ddump('redirecting');
                return redirect("/");
            } else {
                $this->notify('error', 'Login failed; contact administrator.');
            }
        } catch (\Exception $e) {
            ddump($e->getMessage());
            $this->notify('error', "Auth0: Invalid authorization code.");
        }
    }

    public function changeLanguage(string $lang)
    {
        $idLanguage = Base::getIdLanguage($lang);
        AppService::setCurrentLanguage($idLanguage);
        return $this->clientRedirect("/");
    }

    public function changeLevel()
    {
        $login = Manager::getLogin();
        $toLevel = $this->data->id;
        $user = $login->getUser();
        $levels = $user->getAvaiableLevels();
        if ($levels[$toLevel]) {
            $newUser = new fnbr\auth\models\User($levels[$toLevel]);
            $login->setUser($newUser);
            Manager::getSession()->mfnLayers = $newUser->getConfigData('fnbrLayers');
            Manager::getSession()->mfnLevel = $toLevel;
            $this->redirect(Manager::getURL('main'));
        } else {
            return $this->renderPrompt('error', _M('You don\'t have such level.'));
        }

    }

}

