    <x-datagrid
        id="gridFE"
        title="Frame Elements"
        type="child"
        hx-trigger="reload-gridFE from:body"
        hx-target="this"
        hx-swap="outerHTML"
        hx-get="/frame/{{$data->idFrame}}/fes/grid"
    >
        @foreach($data->fes as $fe)
            <tr hx-target="#childPane">
                <td class="wt-datagrid-action">
                    <div
                        class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                        title="delete FE"
                        hx-delete="/fe/{{$fe['idFrameElement']}}"
                    ></div>
                </td>
                <td
                    hx-get="/fe/{{$fe['idFrameElement']}}/edit"
                    hx-target="#childPane"
                    hx-swap="innerHTML"
                    class="cursor-pointer"
                    style="min-width:120px"
                >
                    <span class="{{$fe['iconCls']}}"></span>
                    <span class="color_{{$fe['idColor']}}">{{$fe['name'][0]}}</span>
                </td>
                <td
                    hx-get="/fe/{{$fe['idFrameElement']}}/edit"
                    hx-target="#childPane"
                    hx-swap="innerHTML"
                    class="cursor-pointer"
                >
                    {{$fe['name'][1]}}
                </td>
            </tr>
        @endforeach
    </x-datagrid>
