<div class="grid  h-full">
    <div class="col h-full" style="overflow:auto">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$idFrame}}/classification/formFramalDomain"
        ></div>
    </div>
    <div class="col">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$idFrame}}/classification/formFramalType"
        ></div>
    </div>
</div>


