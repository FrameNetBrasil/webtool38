<div class="grid ">
    <div class="col-4">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/lemma/{{$idLemma}}/lexemes/formNew"
        ></div>
    </div>
    <div class="col-8">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/lemma/{{$idLemma}}/lexemes/grid"
        ></div>
    </div>
</div>
<div
    id="leChildPane"
></div>

