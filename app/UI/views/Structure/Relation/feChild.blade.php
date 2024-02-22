<div class="grid ">
    <div class="col">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/fe/relations/{{$data->idEntityRelation}}/formNew"
        ></div>
    </div>
    <div class="col">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/fe/relations/{{$data->idEntityRelation}}/grid"
        ></div>
    </div>
</div>
<div id="feRelationChildPane"></div>
