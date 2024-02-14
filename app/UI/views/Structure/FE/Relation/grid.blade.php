<x-datagrid
    id="feRelationGrid"
    title="FE Relations"
    extraTitle="<span class='color_{{$data->relationEntry}}'>   {{$data->relationName}}</span>"
    hx-trigger="reload-gridRelationFE from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/fes/relations/{{$data->idEntityRelation}}/grid"
    height="250px"
>
    <x-slot:header>
        <thead>
        <td class="wt-datagrid-action">
        </td>
        <td>
            <span>{{$data->frame->name}}</span>
        </td>
        <td>
            <span>{{$data->relatedFrame->name}}</span>
        </td>
        </thead>
    </x-slot:header>
    @foreach($data->relations as $relation)
        <tr>
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-tree-icon wt-icon-delete"
                    title="delete relation"
                    hx-delete="/fes/relations/{{$relation['idEntityRelation']}}"
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
