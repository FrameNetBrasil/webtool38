<x-datagrid
    id="framesGridRelations"
    title="Frame Relations"
    hx-trigger="reload-gridRelation from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frames/{{$data->idFrame}}/relations/grid"
    height="250px"
>
    @foreach($data->relations as $relation)
        <tr class="">
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-tree-icon wt-icon-delete"
                    title="delete relation"
                    hx-delete="/frames/relations/{{$relation['idEntityRelation']}}"
                ></div>
            </td>
            <td style="width:180px"
                hx-get="/fes/relations/{{$relation['idEntityRelation']}}"
                hx-target="#childPane"
                hx-swap="innerHTML"
                class="cursor-pointer"
            >
                <span class="color_{{$relation['entry']}}">{{$relation['name']}}</span>
            </td>
            <td class="">
                <span>{{$relation['related']}}</span>
            </td>
        </tr>
    @endforeach
</x-datagrid>
