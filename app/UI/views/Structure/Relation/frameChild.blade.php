<div class="grid ">
    <div class="col">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$frame->idFrame}}/relations/formNew"
        ></div>
    </div>
    <div class="col">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$frame->idFrame}}/relations/grid"
        ></div>
    </div>
</div>
<div
    id="relationChildPane"
    hx-trigger="reload-gridFrameRelation from:body"
    hx-target="this"
    hx-swap="innerHTML"
    hx-get="/empty"
></div>
