<x-link-button
    id="menuDocumentEntries"
    label="Translations"
    hx-get="/document/{{$data->document->idDocument}}/entries"
    hx-target="#documentEditPane"
></x-link-button>
