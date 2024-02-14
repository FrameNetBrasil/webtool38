@php
use Maestro\Security\MAuth;
$isLogged = MAuth::isLogged();
if ($isLogged) {
    $user = MAuth::getLogin();
    $userLevel = session('userLevel');
}
$currentLanguage = session('currentLanguage');
$languages = config('webtool.user')[4]['language'][5];
$profile = config('webtool.user')[4]['profile'][5];
$hrefLogin = (env('AUTH0_CLIENT_ID') == 'auth0') ? '/auth0Login' : '/';
@endphp
<x-link-button
        id="udDataset"
        label="{!! config('webtool.db') !!}"
        icon="dataset"
></x-link-button>
<x-menu-button
        id="udLanguage"
        label="{!! $currentLanguage['description'] !!}"
        icon="translate"
        menu="#udMenuLanguage"
></x-menu-button>
    <div id="udMenuLanguage">
        @foreach($languages as $language)
    <div
            data-options="iconCls:'{{$language[2]}}'"
            id="udMenuLanguage{{$language[0]}}"
            hx-get="{{$language[1]}}"
            hx-trigger="click"
    >
        {{$language[0]}}
    </div>
        @endforeach
    </div>
@if($isLogged)
    <x-link-button
            id="udLevel"
            label="{!! $userLevel !!}"
            icon="groups"
    ></x-link-button>
    <x-menu-button
            id="udProfile"
            label="{!! $user->login !!}"
            icon="person"
            menu="#udMenuProfile"
    ></x-menu-button>
    <div id="udMenuProfile">
        @foreach($profile as $p)
            <div
                    data-options="iconCls:'{{$p[2]}}'"
                    id="udMenuProfile{{$p[0]}}"
                    hx-get="{{$p[1]}}" hx-trigger="click"
            >
                {{$p[0]}}
            </div>
        @endforeach
    </div>
@else
    <x-link-button
            id="signin"
            label="Login"
            icon="person"
            href="{{$hrefLogin}}"
    ></x-link-button>
@endif
