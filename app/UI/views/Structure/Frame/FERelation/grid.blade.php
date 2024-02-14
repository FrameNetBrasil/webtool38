<x-datagrid
    id="frameFERelationGrid"
    title="Frame Element Relations"
    hx-trigger="reload-gridFEInternalRelation from:body"
    hx-target="this" hx-swap="outerHTML"
    hx-get="/frames/{{$data->idFrame}}/fes/relations/grid"
    height="250px"
>
    @foreach($data->relations as $relation)
        <tr class="">
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-tree-icon wt-icon-delete"
                    title="delete relation"
                    hx-delete="/frames/fes/relations/{{$relation['idEntityRelation']}}"
                ></div>
            </td>
            <td class="">
                <span class="{{$relation['feIcon']}}"></span>
                <span class="color_{{$relation['feIdColor']}}">{{$relation['feName']}}</span>
            </td>
            <td style="width:180px">
                <span class="color_{{$relation['entry']}}">{{$relation['name']}}</span>
            </td>
            <td class="">
                <span class="{{$relation['relatedFEIcon']}}"></span>
                <span class="color_{{$relation['relatedFEIdColor']}}">{{$relation['relatedFEName']}}</span>
            </td>
        </tr>
    @endforeach
</x-datagrid>
