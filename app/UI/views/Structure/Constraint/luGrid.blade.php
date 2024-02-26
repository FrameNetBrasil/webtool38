<x-datagrid
    id="lusGridConstraints"
    title="LU Constraints"
    type="child"
    hx-trigger="reload-gridConstraintLU from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/lu/{{$idLU}}/constraints/grid"
>
    @foreach($constraints as $constraint)
        <tr>
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete constraint"
                    hx-delete="/constraint/lu/{{$constraint['idConstraintInstance']}}"
                ></div>
            </td>
            <td>
                {{$constraint['relationName']}}
            </td>
            <td>
                {{$constraint['name']}}
            </td>
        </tr>
    @endforeach
</x-datagrid>
