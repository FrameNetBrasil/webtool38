<x-datagrid
    id="gridRT"
    title="Relation Types"
    hx-trigger="reload-gridRT from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/relationgroup/{{$data->idRelationGroup}}/rts/grid"
    height="250px"
>
    @foreach($data->rts as $rt)
        <tr hx-target="#childPane">
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete RT"
                    hx-delete="/relationtype/{{$rt['idRelationType']}}"
                ></div>
            </td>
            <td
                hx-get="/relationtype/{{$rt['idRelationType']}}/edit"
                hx-target="#childPane"
                hx-swap="innerHTML"
                class="cursor-pointer"
            >
                <span>{{$rt['name']}}</span>
            </td>
            <td
                hx-get="/relationtype/{{$rt['idRelationType']}}/edit"
                hx-target="#childPane"
                hx-swap="innerHTML"
                class="cursor-pointer"
            >
                {{$rt['description']}}
            </td>
        </tr>
    @endforeach
</x-datagrid>
