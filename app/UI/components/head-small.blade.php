@php
    use Orkester\Security\MAuth;
    use App\Data\MenuData;

    $actions = config('webtool.actions');
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
<header id="headSmall">
    <nav id="hxTopNav">
        <div class="hxTopNavIcon">
            <hx-disclosure aria-controls="leftNav">
                <hx-icon type="list"></hx-icon>
            </hx-disclosure>
        </div>
        <div class="hxTopNavApp">
            <hx-disclosure>
                <a href="/">
                    <p>{!! config('webtool.headerTitle') !!}</p>
                </a>
            </hx-disclosure>
        </div>
        @if($isLogged)
            <div class="hxTopNavIconMenu">
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
            </div>
        @endif
    </nav>
    <hx-reveal id="leftNav">
        <nav id="nav" class="hxNav hxLeftNavSection">
            @foreach($actions as $id => $action)
                @php
                    $menuData = MenuData::from([
                        'id' => $id . '_small',
                        'label' => $action[0],
                        'href' => $action[1],
                        'group' => $action[2],
                        'items' => $action[3]
                    ]);
                @endphp
                @if (MAuth::checkAccess($menuData->group))
                    <hx-disclosure aria-controls="menu{{$menuData->id}}" aria-expanded="false">
                        <span>{!! $menuData->label !!}</span>
                        <hx-icon class="toggle-icon" type="angle-down"></hx-icon>
                    </hx-disclosure>
                    <hx-reveal class="hxLeftNavSubSection" id="menu{{$menuData->id}}" aria-expanded="false">
                        @foreach($menuData->items as $idItem => $item)
                            @php
                                $itemData = MenuData::from([
                                    'id' => $idItem . '_small',
                                    'label' => $item[0],
                                    'href' => $item[1],
                                    'group' => $item[2],
                                    'items' => $item[3]
                                ]);
                            @endphp
                            <a href="{{$itemData->href}}">{{$itemData->label}}</a>
                        @endforeach
                    </hx-reveal>
                @endif
            @endforeach
        </nav>
    </hx-reveal>
</header>
