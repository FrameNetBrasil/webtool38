<x-datagrid
    id="gridFrameRelation"
    title="Frame Relations"
    type="child"
    hx-trigger="reload-gridFrameRelation from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frame/{{$data->idFrame}}/relations/grid"
>
    @foreach($data->relations as $relation)
        <tr class="">
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete relation"
                    hx-delete="/relation/frame/{{$relation['idEntityRelation']}}"
                ></div>
            </td>
            <td
                hx-get="/fe/relations/{{$relation['idEntityRelation']}}"
                hx-target="#childPane"
                hx-swap="innerHTML"
                class="cursor-pointer"
            >
                <span class="color_{{$relation['entry']}}">{{$relation['name']}}</span>
            </td>
            <td>
                <span>{{$relation['related']}}</span>
            </td>
        </tr>
    @endforeach
</x-datagrid>
