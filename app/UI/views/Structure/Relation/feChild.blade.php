<x-layout.child>
    <x-slot:left>
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/fe/relations/{{$data->idEntityRelation}}/formNew"
        ></div>
    </x-slot:left>
    <x-slot:right>
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/fe/relations/{{$data->idEntityRelation}}/grid"
        ></div>
    </x-slot:right>
</x-layout.child>