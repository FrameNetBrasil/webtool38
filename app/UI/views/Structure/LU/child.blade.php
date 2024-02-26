<div class="grid ">
    <div class="col-4">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$idFrame}}/lus/formNew"
        ></div>
    </div>
    <div class="col-8">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$idFrame}}/lus/grid"
        ></div>
    </div>
</div>
<div
    id="luChildPane"
    hx-trigger="reload-gridLU from:body"
    hx-target="this"
    hx-swap="innerHTML"
    hx-get="/empty"
></div>
