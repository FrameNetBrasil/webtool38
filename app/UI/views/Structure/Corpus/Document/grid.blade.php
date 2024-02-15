<x-datagrid
    id="corpusDocumentGrid"
    title="Documents"
    hx-trigger="reload-gridDocument from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/corpus/{{$data->idCorpus}}/documents/grid"
    height="250px"
>
    @foreach($data->documents as $document)
        <tr hx-target="#childPane">
            <td class="wt-datagrid-action">
                <span
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete Document"
                    hx-delete="/document/{{$document['idDocument']}}"
                ></span>
            </td>
            <td
                hx-get="/document/{{$document['idDocument']}}/edit"
                hx-target="#childPane"
                hx-swap="innerHTML"
                class="cursor-pointer"
            >
                <span>{{$document['name']}}</span>
            </td>
        </tr>
    @endforeach
</x-datagrid>
