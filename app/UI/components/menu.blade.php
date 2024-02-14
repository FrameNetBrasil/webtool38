@php
    use Maestro\Security\MAuth;

    $actions = config('webtool.actions');
    $isLogged = MAuth::isLogged();
    if ($isLogged) {
        $user = MAuth::getLogin();
    }
@endphp
@foreach($actions as $menu => $action)
    @if (($action[4] == '') || MAuth::checkAccess($action[3]))
        <x-menu-button
                id="menu{{$menu}}"
                label="{!! $action[0] !!}"
                icon="{{$action[2]}}"
                menu="#menu{{$menu}}Items"
        ></x-menu-button>
        <div id="menu{{$menu}}Items">
            @foreach($action[5] as $item)
                <div
                        data-options="iconCls:'material-icons-outlined wt-button-icon wt-icon-{{$item[2]}}',href:'{{$item[1]}}'"
                        id="menu{{$menu}}Item"
                >
                    {{$item[0]}}
                </div>
            @endforeach
        </div>
    @endif
@endforeach
