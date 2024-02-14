<x-layout.content>
    <x-layout.child>
        <x-slot:left>
            <div
                hx-trigger="load"
                hx-target="this"
                hx-swap="outerHTML"
                hx-get="/fes/{{$data->idFrameElement}}/constraints/formNew"
            ></div>
        </x-slot:left>
        <x-slot:right>
            <div
                hx-trigger="load"
                hx-target="this"
                hx-swap="outerHTML"
                hx-get="/fes/{{$data->idFrameElement}}/constraints/grid"
            ></div>
        </x-slot:right>
    </x-layout.child>
</x-layout.content>
