<x-datagrid
    id="gridChildST"
    title="SemanticTypes"
    type="child"
    hx-trigger="reload-gridChildST from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/semanticType/{{$data->idEntity}}/childGrid"
>
    @foreach($data->relations as $relation)
        <tr>
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete relation"
                    hx-delete="/semanticType/{{$relation['idEntityRelation']}}"
                ></div>
            </td>
            <td>
                <span>{{$relation['name']}}</span>
            </td>
        </tr>
    @endforeach
</x-datagrid>
