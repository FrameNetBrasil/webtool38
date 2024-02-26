<div class="grid ">
    <div class="col">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$idFrame}}/feRelations/formNew"
        ></div>
    </div>
    <div class="col">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$idFrame}}/feRelations/grid"
        ></div>
    </div>
</div>


