<div class="grid ">
    <div class="col-4">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/relationgroup/{{$idRelationGroup}}/rts/formNew"
        ></div>
    </div>
    <div class="col-8">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/relationgroup/{{$idRelationGroup}}/rts/grid"
        ></div>
    </div>
</div>
<div
    id="rtChildPane"
    hx-trigger="reload-gridRT from:body"
    hx-target="this"
    hx-swap="innerHTML"
    hx-get="/empty"
></div>
