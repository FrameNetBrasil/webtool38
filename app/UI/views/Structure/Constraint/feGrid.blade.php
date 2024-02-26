<x-datagrid
    id="feConstraintGrid"
    title="FE Constraints"
    type="child"
    hx-trigger="reload-gridConstraintFE from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/fes/{{$data->idFrameElement}}/constraints/grid"
>
    @foreach($data->constraints as $constraint)
        <tr>
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-tree-icon wt-icon-delete"
                    title="delete FE Constraint"
                    hx-delete="/fes/constraints/{{$constraint['idConstraint']}}"
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
