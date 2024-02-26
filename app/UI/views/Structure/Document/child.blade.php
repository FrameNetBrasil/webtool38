<div class="grid ">
    <div class="col-4">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/corpus/{{$idCorpus}}/documents/formNew"
        ></div>
    </div>
    <div class="col-8">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/corpus/{{$idCorpus}}/documents/grid"
        ></div>
    </div>
</div>
<div
    id="docChildPane"
    hx-trigger="reload-gridDocument from:body"
    hx-target="this"
    hx-swap="innerHTML"
    hx-get="/empty"
></div>

