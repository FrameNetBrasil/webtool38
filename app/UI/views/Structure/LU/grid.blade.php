<x-datagrid
    id="gridLU"
    title="LUs"
    hx-trigger="reload-gridLU from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frame/{{$data->idFrame}}/lus/grid"
    height="250px"
>
    @foreach($data->lus as $lu)
        <tr hx-target="#childPane" >
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete LU"
                    hx-delete="/lu/{{$lu['idLU']}}"
                ></div>
            </td>
            <td
                hx-get="/lu/{{$lu['idLU']}}"
                hx-target="#childPane"
                hx-swap="innerHTML"
                class="cursor-pointer"
            >
                <span>{{$lu['name']}}</span>
            </td>
            <td
                hx-get="/lu/{{$lu['idLU']}}"
                hx-target="#childPane"
                hx-swap="innerHTML"
                class="cursor-pointer"
            >
                {{$lu['senseDescription']}}
            </td>
        </tr>
    @endforeach
</x-datagrid>
