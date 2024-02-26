<x-datagrid
    id="corpusDocumentGrid"
    title="Documents"
    type="child"
    hx-trigger="reload-gridDocument from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/corpus/{{$idCorpus}}/documents/grid"
>
    @foreach($documents as $document)
        <tr
            hx-target="#docChildPane"
            hx-swap="innerHTML"
        >
            <td class="wt-datagrid-action">
                <span
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete Document"
                    hx-delete="/document/{{$document['idDocument']}}"
                ></span>
            </td>
            <td
                hx-get="/document/{{$document['idDocument']}}/edit"
                class="cursor-pointer"
            >
                <span>{{$document['name']}}</span>
            </td>
        </tr>
    @endforeach
</x-datagrid>
