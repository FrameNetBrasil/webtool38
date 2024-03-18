@php
    use Orkester\Security\MAuth;
    $isLogged = MAuth::isLogged();
    if ($isLogged) {
        $user = MAuth::getLogin();
        $userLevel = session('userLevel');
    }
    $currentLanguage = session('currentLanguage');
    $languages = config('webtool.user')[3]['language'][3];
    $profile = config('webtool.user')[3]['profile'][3];
    $hrefLogin = (env('AUTH0_CLIENT_ID') == 'auth0') ? '/auth0Login' : '/';
@endphp
<div class="hxTopNavIconMenu">
    <div class="hxTopNavMenu">
        <hx-disclosure aria-controls="menuLanguage" aria-expanded="false">
            <span class="icon material-icons-outlined wt-icon-menu-translate"></span>
            <span>{!! $currentLanguage['description'] !!}</span>
            <hx-icon class="hxPrimary" type="angle-down"></hx-icon>
        </hx-disclosure>
        <hx-menu id="menuLanguage">
            <section>
                @foreach($languages as $language)
                    <hx-menuitem hx-get="{{$language[1]}}" hx-trigger="click">{{$language[0]}}</hx-menuitem>
                @endforeach
            </section>
        </hx-menu>
    </div>
    @if($isLogged)
        <div class="hxSpacer"></div>
        <div class="hxTopNavMenu">
            <hx-disclosure aria-controls="demo-user-menu" aria-expanded="false">
                <hx-icon class="hxNavUser" type="user"></hx-icon>
                <span>{{$user->email}}</span>
                <hx-icon class="hxPrimary" type="angle-down"></hx-icon>
            </hx-disclosure>
            <hx-menu id="demo-user-menu" position="bottom-end">
                <section>
                    <header>
                        <hx-menuitem class="hxMenuKey">Level:</hx-menuitem>
                        <hx-menuitem class="hxMenuValue">{{$userLevel}}</hx-menuitem>
                    </header>
                    <hr class="hxDivider">
                    <hx-menuitem class="hxMenuValue">My Profile</hx-menuitem>
                    <hr class="hxDivider">
                    <footer>
                        <button class="hxBtn" hx-get="/logout">Log Out</button>
                    </footer>
                </section>
            </hx-menu>
        </div>
    @else
        <a href="{{$hrefLogin}}">
            <hx-icon class="material-icons-outlined wt-icon-menu-signin"></hx-icon>
            <p>Login</p>
        </a>
    @endif
</div>
