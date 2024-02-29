<div
    class="wt-datagrid"
    style="padding: 16px"
    hx-trigger="reload-gridObjects from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/annotation/dynamicMode/gridObjects;{{$idDocument}}"
>
    <table>
        <thead>
            <tr>
                <th>x</th>
                <th>y</th>
            </tr>
        </thead>
        <tbody>
        @foreach($objects as $object)
            <tr>
                <td class="wt-datagrid-action">
                <span
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete Document"
                    hx-delete="/annotation/dynamicMode/object/1"
                ></span>
                </td>
                <td
                >
                    <span>xxxhhh</span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
