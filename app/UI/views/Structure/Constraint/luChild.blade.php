<div class="grid ">
    <div class="col">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/lu/{{$idLU}}/constraints/formNew"
        ></div>
    </div>
    <div class="col">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/lu/{{$idLU}}/constraints/grid"
        ></div>
    </div>
</div>
<div id="luConstraintChildPane"></div>