<div class="grid ">
    <div class="col-4">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$idFrame}}/fes/formNew"
        ></div>
    </div>
    <div class="col-8">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$idFrame}}/fes/grid"
        ></div>
    </div>
</div>
<div
    id="feChildPane"
    hx-trigger="reload-gridFE from:body"
    hx-target="this"
    hx-swap="innerHTML"
    hx-get="/empty"
></div>

