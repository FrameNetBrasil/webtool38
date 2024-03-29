<x-layout.content>
    <x-layout.child>
        <x-slot:left>
            <div
                hx-trigger="load"
                hx-target="this"
                hx-swap="outerHTML"
                hx-get="/frames/{{$data->frame->idFrame}}/lus/formNew"
            ></div>
        </x-slot:left>
        <x-slot:right>
            <div
                hx-trigger="load"
                hx-target="this"
                hx-swap="outerHTML"
                hx-get="/frames/{{$data->frame->idFrame}}/lus/grid"
            ></div>
        </x-slot:right>
    </x-layout.child>
</x-layout.content>
