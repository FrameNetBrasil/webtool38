<x-datagrid id="usersGroupGrid" title="Groups" hx-trigger="reload-gridGroup from:body" hx-target="this"  hx-swap="outerHTML" hx-get="/users/{{$data->idUser}}/groups/grid" height="250px">
    @foreach($data->groups as $group)
        <tr hx-target="#usersGroupPane" >
            <td class="wt-datagrid-action">
                <div class="action material-icons-outlined wt-tree-icon wt-icon-delete" title="delete fe"  hx-delete="/users/{{$data->idUser}}/groups/{{$group['idGroup']}}"></div>
            </td>
            <td style="width:180px">
                <span>{{$group['name']}}</span>
            </td>
        </tr>
    @endforeach
</x-datagrid>
