<x-datagrid
    id="frameSemanticTypeGrid"
    title="SemanticTypes"
    hx-trigger="reload-gridSTRelation from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frames/{{$data->idFrame}}/semanticTypes/grid"
    height="250px"
>
    @foreach($data->relations as $relation)
        <tr class="">
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete relation"
                    hx-delete="/frames/semanticTypes/{{$relation['idEntityRelation']}}"
                ></div>
            </td>
            <td>
                <span>{{$relation['name']}}</span>
            </td>
        </tr>
    @endforeach
</x-datagrid>
