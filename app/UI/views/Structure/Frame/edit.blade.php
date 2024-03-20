<x-layout.edit>
    <x-slot:title>
        Frames
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="secondary" href="/frame"></x-button>
    </x-slot:actions>
    <x-slot:name>
        {{$frame?->name}}
    </x-slot:name>
    <x-slot:detail>
        @foreach ($classification as $name => $values)
            @foreach ($values as $value)
                <x-tag label="{{$value}}"></x-tag>
            @endforeach
        @endforeach
        @if($isAdmin)
            <x-button
                label="Delete"
                color="danger"
                onclick="manager.confirmDelete(`Removing Frame '{{$frame?->name}}'. Confirm?`, '/frame/{{$frame->idFrame}}')"
            ></x-button>
        @endif
    </x-slot:detail>
    <x-slot:description>
        {{$frame?->description}}
    </x-slot:description>
    <x-slot:edit>
        <div id="editPanel">
        </div>
    </x-slot:edit>
</x-layout.edit>
