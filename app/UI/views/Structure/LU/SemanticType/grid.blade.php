<x-datagrid id="stLUGridRelations" title="SemanticTypes" hx-trigger="reload-gridSTLURelation from:body" hx-target="this" hx-swap="outerHTML"
            hx-get="/lus/{{$data->idLU}}/semanticTypes/grid" height="250px">
    @foreach($data->relations as $relation)
        <tr class="">
            <td class="wt-datagrid-action">
                <div class="action material-icons-outlined wt-tree-icon wt-icon-delete" title="delete relation"  hx-delete="/lus/relations/{{$relation['idEntityRelation']}}"></div>
            </td>
            <td class="">
                <span>{{$relation['name']}}</span>
            </td>
        </tr>
    @endforeach
</x-datagrid>