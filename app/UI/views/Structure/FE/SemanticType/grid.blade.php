<x-datagrid id="stFEGridRelations" title="SemanticTypes" hx-trigger="reload-gridSTFERelation from:body" hx-target="this" hx-swap="outerHTML"
            hx-get="/fes/{{$data->idFrameElement}}/semanticTypes/grid" height="250px">
    @foreach($data->relations as $relation)
        <tr class="">
            <td class="wt-datagrid-action">
                <div class="action material-icons-outlined wt-tree-icon wt-icon-delete" title="delete relation"  hx-delete="/fes/semanticTypes/{{$relation['idEntityRelation']}}"></div>
            </td>
            <td class="">
                <span>{{$relation['name']}}</span>
            </td>
        </tr>
    @endforeach
</x-datagrid>