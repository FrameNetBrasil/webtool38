<x-layout.content>
    <x-datagrid
        id="gridRT"
        title="Relation Types"
        type="child"
        hx-trigger="reload-gridRT from:body"
        hx-target="this"
        hx-swap="outerHTML"
        hx-get="/relationgroup/{{$idRelationGroup}}/rts/grid"
    >
        @foreach($rts as $rt)
            <tr
                hx-target="#rtChildPane"
                hx-swap="innerHTML"
            >
                <td class="wt-datagrid-action">
                    <div
                        class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                        title="delete RT"
                        hx-delete="/relationtype/{{$rt['idRelationType']}}"
                    ></div>
                </td>
                <td
                    hx-get="/relationtype/{{$rt['idRelationType']}}/edit"
                    class="cursor-pointer"
                >
                    <span>{{$rt['name']}}</span>
                </td>
                <td
                    hx-get="/relationtype/{{$rt['idRelationType']}}/edit"
                    class="cursor-pointer"
                >
                    {{$rt['description']}}
                </td>
            </tr>
        @endforeach
    </x-datagrid>
</x-layout.content>
