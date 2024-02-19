<x-datagrid
    id="lusGridConstraints"
    title="LU Constraints"
    hx-trigger="reload-gridConstraintLU from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/lu/{{$data->idLU}}/constraints/grid" height="250px"
>
    @foreach($data->constraints as $constraint)
        <tr>
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete constraint"
                    hx-delete="/lu/constraints/{{$constraint['idConstraint']}}"
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
