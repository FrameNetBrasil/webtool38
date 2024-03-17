@php
    use Orkester\Security\MAuth;
    use App\Data\MenuData;

    $actions = config('webtool.actions');
    $isLogged = MAuth::isLogged();
    if ($isLogged) {
        $user = MAuth::getLogin();
    }
@endphp
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
