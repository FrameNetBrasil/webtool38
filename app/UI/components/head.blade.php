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
<header id="head">
    <nav id="hxTopNav">
        <div class="hxTopNavApp">
            <hx-disclosure>
                <a href="/">
                    <p>{!! config('webtool.headerTitle') !!}</p>
                </a>
            </hx-disclosure>
        </div>
        <div class="hxTopNavMenu hxTopNavOptionMenu">
            @foreach($actions as $id => $action)
                @php
                    $menuData = MenuData::from([
                        'id' => $id,
                        'label' => $action[0],
                        'href' => $action[1],
                        'group' => $action[2],
                        'items' => $action[3]
                    ]);
                @endphp
                @if (MAuth::checkAccess($menuData->group))
                    <hx-disclosure aria-controls="menu{{$menuData->id}}" aria-expanded="false">
                        <span>{!! $menuData->label !!}</span>
                        <hx-icon class="hxPrimary" type="angle-down"></hx-icon>
                    </hx-disclosure>
                    <hx-menu id="menu{{$menuData->id}}">
                        <section>
                            @foreach($menuData->items as $idItem => $item)
                                @php
                                    $itemData = MenuData::from([
                                        'id' => $idItem,
                                        'label' => $item[0],
                                        'href' => $item[1],
                                        'group' => $item[2],
                                        'items' => $item[3]
                                    ]);
                                @endphp
                                <hx-menuitem><a href="{{$itemData->href}}">{{$itemData->label}}</a></hx-menuitem>
                            @endforeach
                        </section>
                    </hx-menu>
                @endif
            @endforeach
        </div>
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
                <div class="hxTopNavMenu">
                    <a href="{{$hrefLogin}}">
                        <hx-icon class="material-icons-outlined wt-icon-menu-signin"></hx-icon>
                        <p>Login</p>
                    </a>
                </div>
            @endif
        </div>
    </nav>
</header>
