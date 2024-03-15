<?php

namespace App\Http\Controllers\Index;

use App\Exceptions\LoginException;
use App\Exceptions\UserNewException;
use App\Exceptions\UserPendingException;
use App\Http\Controllers\Controller;
use App\Repositories\Base;
use App\Repositories\ViewFrame;
use App\Services\AppService;
use App\Services\AuthUserService;
use Auth0\SDK\Auth0;
use Auth0\SDK\Exception\StateException;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Orkester\Security\MAuth;

#[Middleware(name: 'web')]
class MainController extends Controller
{
    #[Get(path: '/')]
    public function main()
    {
        if (MAuth::isLogged()) {

            $f = new ViewFrame();
            $r = $f->listByFilter(null);

            return $this->render('formMain');
        } else {
            if (config('webtool.login.handler') == 'auth0') {
                return $this->render('auth0Login');
                //return view('Index.Main.auth0Login');
            } else {
                $this->data->challenge = uniqid(rand());
                session(['challenge', $this->data->challenge]);
                return $this->render('formLogin');
            }
        }
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


    #[Get(path: '/main/auth0Callback')]
    public function auth0Callback()
    {
        try {
            $auth0 = $this->getAuth0();
            $auth0->exchange($this->data->redirect_uri);

            $userInfo = $auth0->getUser();
            $user = new AuthUserService();
            $status = $user->auth0Login($userInfo);

            if ($status == 'new') {
                throw new UserNewException('User registered. Wait for Administrator approval.');
            } elseif ($status == 'pending') {
                throw new UserPendingException('User already registered, but waiting for Administrator approval.');
            } elseif ($status == 'logged') {
                return redirect("/");
            } else {
                throw new LoginException('Login failed; contact administrator.');
            }
        } catch (StateException $e) {
            throw new LoginException("Auth0: Invalid authorization code.");
        }
    }

    #[Get(path: '/changeLanguage/{language}')]
    public function changeLanguage(string $language)
    {
        $idLanguage = Base::getIdLanguage($language);
        AppService::setCurrentLanguage($idLanguage);
        return $this->clientRedirect("/");
    }

    #[Get(path: '/auth0Login')]
    public function auth0Login()
    {
        $auth0 = $this->getAuth0();
        $auth0->clear();
        header("Location: " . $auth0->login($this->data->redirect_uri));
        exit;
    }

    #[Post(path: '/login')]
    public function login()
    {
        $userInfo = $this->data;
        $user = new AuthUserService();
        $status = $user->md5Login($userInfo);

        if ($status == 'new') {
            throw new UserNewException('User registered. Wait for Administrator approval.');
        } elseif ($status == 'pending') {
            throw new UserPendingException('User already registered, but waiting for Administrator approval.');
        } elseif ($status == 'logged') {
            return $this->clientRedirect("/");
        } else {
            throw new LoginException('Login failed; contact administrator.');
        }
    }

    #[Get(path: '/logout')]
    public function logout()
    {
        MAuth::logout();
        if (config('webtool.login.handler') == 'auth0') {
            $auth0 = $this->getAuth0();
            $auth0->logout('/');
        }
        return $this->clientRedirect("/");
    }


}
