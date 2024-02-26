<x-datagrid
    id="gridLU"
    title="LUs"
    type="child"
    hx-trigger="reload-gridLU from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frame/{{$idFrame}}/lus/grid"
>
    @foreach($lus as $lu)
        <tr
            hx-target="#luChildPane"
            hx-swap="innerHTML"
        >
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete LU"
                    hx-delete="/lu/{{$lu['idLU']}}"
                ></div>
            </td>
            <td
                hx-get="/lu/{{$lu['idLU']}}/edit"
                class="cursor-pointer"
            >
                <span>{{$lu['name']}}</span>
            </td>
            <td
                hx-get="/lu/{{$lu['idLU']}}/edit"
                class="cursor-pointer"
            >
                {{$lu['senseDescription']}}
            </td>
        </tr>
    @endforeach
</x-datagrid>
