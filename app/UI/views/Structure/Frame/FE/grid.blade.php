<x-datagrid
    id="frameFEGrid"
    title="Frame Elements"
    hx-trigger="reload-gridFE from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frames/{{$data->idFrame}}/fes/grid"
    height="250px"
>
    @foreach($data->fes as $fe)
        <tr hx-target="#childPane">
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-tree-icon wt-icon-delete"
                    title="delete FE"
                    hx-delete="/fes/{{$fe['idFrameElement']}}"
                ></div>
            </td>
            <td
                style="width:180px"
                hx-get="/fes/{{$fe['idFrameElement']}}/edit"
                hx-target="#childPane"
                hx-swap="innerHTML"
                class="cursor-pointer"
            >
                <span class="{{$fe['iconCls']}}"></span>
                <span class="color_{{$fe['idColor']}}">{{$fe['name']}}</span>
            </td>
            <td
                hx-get="/fes/{{$fe['idFrameElement']}}/edit"
                hx-target="#childPane"
                hx-swap="innerHTML"
                class="cursor-pointer"
            >
                {{$fe['description']}}
            </td>
        </tr>
    @endforeach
</x-datagrid>
