<x-dynamic-component component="layout.{{$data->_layout}}">
    <x-slot:title>
        Relation Type
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="primary" href="/relationtype"></x-button>
        <x-button label="New" color="secondary" href="/relationtype/new"></x-button>
    </x-slot:actions>
    <x-slot:edit>
        @if(isset($data->relationType))
            <div class="grid grid-nogutter">
                <div class="col-8 title">
                    <span class="color_generic">{{$data->relationType?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    <x-tag label="{{$data->relationType->relationGroup->name}}"></x-tag>
                    <x-tag label="#{{$data->relationType->idRelationType}}"></x-tag>
                    @if($data->_layout == 'main')
                        <x-button
                            label="Delete"
                            color="danger"
                            onclick="manager.confirmDelete(`Removing RelationType '{{$data->relationType?->name}}'. Confirm?`, '/relationtype/{{$data->relationType->idRelationType}}')"
                        ></x-button>
                    @endif
                </div>
            </div>
            @if($data->_layout == 'main')
                <div class="description">{{$data->relationType?->description}}</div>
            @endif
        @endif
    </x-slot:edit>
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
