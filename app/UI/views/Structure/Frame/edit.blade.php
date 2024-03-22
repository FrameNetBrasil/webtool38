<x-layout.edit>
    <x-slot:title>
        Frames
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="secondary" href="/frame"></x-button>
    </x-slot:actions>
    <x-slot:master>
        <x-layout.object>
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
            <x-slot:nav>
                <a hx-get="/frame/{{$frame->idFrame}}/entries"
                   hx-target="#editFrame"
                >Translations
                </a>
                <a hx-get="/frame/{{$frame->idFrame}}/fes"
                   hx-target="#editFrame"
                >FrameElements
                </a>
                <a
                    hx-get="/frame/{{$frame->idFrame}}/lus"
                    hx-target="#editFrame"
                >LUs</a>
                <a
                    hx-get="/frame/{{$frame->idFrame}}/classification"
                    hx-target="#editFrame"
                >Classification</a>
                <a
                    hx-get="/frame/{{$frame->idFrame}}/relations"
                    hx-target="#editFrame"
                >Relations</a>
                <a
                    hx-get="/frame/{{$frame->idFrame}}/feRelations"
                    hx-target="#editFrame"
                >FE-Relations</a>
                <a
                    hx-get="/frame/{{$frame->idFrame}}/semanticTypes"
                    hx-target="#editFrame"
                >SemanticTypes</a>

            </x-slot:nav>
            <x-slot:main>
                <div id="editFrame" class="editArea">
                </div>
            </x-slot:main>
        </x-layout.object>
    </x-slot:master>
    <x-slot:detail>
    </x-slot:detail>
</x-layout.edit>
