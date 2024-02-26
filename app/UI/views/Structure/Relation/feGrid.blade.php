<x-datagrid
    id="gridFERelation"
    title="FE Relations"
    type="child"
    extraTitle="<span class='color_{{$relation->entry}}'>   {{$relation->name}}</span>"
    hx-trigger="reload-gridFERelation from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/fe/relations/{{$idEntityRelation}}/grid"
>
    <x-slot:header>
        <thead>
        <td class="wt-datagrid-action">
        </td>
        <td>
            <span>{{$frame->name}}</span>
        </td>
        <td>
            <span>{{$relatedFrame->name}}</span>
        </td>
        </thead>
    </x-slot:header>
    @foreach($relations as $relation)
        <tr>
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-tree-icon wt-icon-delete"
                    title="delete relation"
                    hx-delete="/relation/fe/{{$relation['idEntityRelation']}}"
                ></div>
            </td>
            <td>
                <span class="{{$relation['feIconCls']}}"></span>
                <span class="color_{{$relation['feIdColor']}}">{{$relation['feName']}}</span>
            </td>
            <td>
                <span class="{{$relation['relatedFEIconCls']}}"></span>
                <span class="color_{{$relation['relatedFEIdColor']}}">{{$relation['relatedFEName']}}</span>
            </td>
        </tr>
    @endforeach
</x-datagrid>
