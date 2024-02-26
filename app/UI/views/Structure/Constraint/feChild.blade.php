<div class="grid ">
    <div class="col">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/fe/{{$idFrameElement}}/constraints/formNew"
        ></div>
    </div>
    <div class="col">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/fe/{{$idFrameElement}}/constraints/grid"
        ></div>
    </div>
</div>
<div id="feConstraintChildPane"></div>
