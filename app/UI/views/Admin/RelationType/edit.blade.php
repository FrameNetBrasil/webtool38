<x-dynamic-component component="layout.{{$data->_layout}}">
    <x-slot:header>
        @if(isset($data->relationType))
            <div class="grid grid-nogutter">
                <div class="col-8 title">
                    <span>RelationType: {{$data->relationType?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    <span>[#{{$data->relationType->relationGroup->name}}]</span>
                    <span>[#{{$data->relationType->idRelationType}}]</span>
                </div>
            </div>
        @endif
    </x-slot:header>
    <x-slot:nav>
        <div class="options">
            <x-link-button
                id="menuRTEdit"
                label="Edit"
                hx-get="/relationtype/{{$data->relationType->idRelationType}}/formEdit"
                hx-target="#childEditPane"
            ></x-link-button>
            <x-link-button
                id="menuRTEntries"
                label="Translations"
                hx-get="/relationtype/{{$data->relationType->idRelationType}}/entries"
                hx-target="#childEditPane"
            ></x-link-button>
        </div>
    </x-slot:nav>
    <x-slot:main>
        <div id="childEditPane">
        </div>
    </x-slot:main>
</x-dynamic-component>
