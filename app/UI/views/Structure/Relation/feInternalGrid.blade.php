<x-datagrid
    id="frameFERelationGrid"
    title="Frame Element Relations"
    type="child"
    hx-trigger="reload-gridFEInternalRelation from:body"
    hx-target="this" hx-swap="outerHTML"
    hx-get="/frame/{{$idFrame}}/feRelations/grid"
>
    @foreach($relations as $relation)
        <tr class="">
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete relation"
                    hx-delete="/relation/feRelation/{{$relation['idEntityRelation']}}"
                ></div>
            </td>
            <td>
                <span class="{{$relation['feIcon']}}"></span>
                <span class="color_{{$relation['feIdColor']}}">{{$relation['feName']}}</span>
            </td>
            <td>
                <span class="color_{{$relation['entry']}}">{{$relation['name']}}</span>
            </td>
            <td>
                <span class="{{$relation['relatedFEIcon']}}"></span>
                <span class="color_{{$relation['relatedFEIdColor']}}">{{$relation['relatedFEName']}}</span>
            </td>
        </tr>
    @endforeach
</x-datagrid>
