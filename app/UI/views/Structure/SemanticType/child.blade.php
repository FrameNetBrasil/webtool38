<div class="grid ">
    <div class="col-4">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/semanticType/{{$data->idEntity}}/childAdd/{{$data->root}}"
        ></div>
    </div>
    <div class="col-8">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/semanticType/{{$data->idEntity}}/childGrid"
        ></div>
    </div>
</div>
<div
    id="stChildPane"
    hx-trigger="reload-gridRT from:body"
    hx-target="this"
    hx-swap="innerHTML"
    hx-get="/empty"
></div>
