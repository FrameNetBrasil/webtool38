@php
    use Maestro\Security\MAuth;
    use App\Data\MenuData;

    $actions = config('webtool.actions');
    $isLogged = MAuth::isLogged();
    if ($isLogged) {
        $user = MAuth::getLogin();
    }
@endphp
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
        <x-menu-button
            id="menu{{$menuData->id}}"
            label="{!! $menuData->label !!}"
            icon="menu-{{$menuData->id}}"
            menu="#menu{{$menuData->id}}Items"
            class="{!! ($menuData->id != 'report') ? 'desktop-only' : '' !!}"
        ></x-menu-button>
        <div id="menu{{$menuData->id}}Items">
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
                <div
                    id="menu{{$menuData->id}}Item{{$itemData->id}}"
                    data-options="href:'{{$itemData->href}}'"
                >
                    {{$itemData->label}}
                </div>
            @endforeach
        </div>
    @endif
@endforeach
