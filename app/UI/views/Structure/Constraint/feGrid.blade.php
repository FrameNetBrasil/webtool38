<x-datagrid
    id="feConstraintGrid"
    title="FE Constraints"
    type="child"
    hx-trigger="reload-gridConstraintFE from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/fe/{{$idFrameElement}}/constraints/grid"
>
    @foreach($constraints as $constraint)
        <tr>
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-tree-icon wt-icon-delete"
                    title="delete FE Constraint"
                    hx-delete="/constraint/fe/{{$constraint['idConstraintInstance']}}"
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
