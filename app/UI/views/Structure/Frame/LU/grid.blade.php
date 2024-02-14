<x-datagrid
    id="framesGridLU"
    title="LUs"
    hx-trigger="reload-gridLU from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frames/{{$data->idFrame}}/lus/grid"
    height="250px"
>
    @foreach($data->lus as $lu)
        <tr hx-target="#framesLUPane" >
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-tree-icon wt-icon-delete"
                    title="delete LU"
                    hx-delete="/lus/{{$lu['idLU']}}"
                ></div>
            </td>
            <td
                style="width:180px"
                hx-get="/lus/{{$lu['idLU']}}/edit"
                hx-target="#childPane"
                hx-swap="innerHTML"
                class="cursor-pointer"
            >
                <span>{{$lu['name']}}</span>
            </td>
            <td
                hx-get="/lus/{{$lu['idLU']}}/edit"
                hx-target="#childPane"
                hx-swap="innerHTML"
                class="cursor-pointer"
            >
                {{$lu['senseDescription']}}
            </td>
        </tr>
    @endforeach
</x-datagrid>
