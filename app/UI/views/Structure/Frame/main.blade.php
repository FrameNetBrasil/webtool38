<x-layout.main>
    <x-slot:title>
        Frames
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="primary" href="/frame"></x-button>
        <x-button label="New" color="secondary" href="/frame/new"></x-button>
    </x-slot:actions>
    <x-slot:edit>
        @if($data->_action == 'edit')
            <div class="grid grid-nogutter">
                <div class="col-8 title">
                    <span class="color_frame">{{$data->frame?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    @foreach ($data->classification as $name => $values)
                        [
                        @foreach ($values as $value)
                            {{$value}}
                        @endforeach
                        ]
                    @endforeach
                </div>
            </div>
            <div class="description">{{$data->frame?->description}}</div>
        @endif
    </x-slot:edit>
    <x-slot:nav>
        @if($data->_action == 'browse')
            <x-form-search id="frameSlotSearch">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <x-input-field id="search_frame" :value="$data->search->frame ?? ''"
                               placeholder="Search Frame"></x-input-field>
                <x-input-field id="search_fe" :value="$data->search->fe ?? ''" placeholder="Search FE"></x-input-field>
                <x-input-field id="search_lu" :value="$data->search->lu ?? ''" placeholder="Search LU"></x-input-field>
                <x-combobox.frame-classification id="search_listBy" placeholder="List by"
                                                 value=""></x-combobox.frame-classification>
                <x-submit label="Search" hx-post="/frame/grid" hx-target="#framePane"></x-submit>
            </x-form-search>
        @endif
        @if($data->_action == 'edit')
            <div class="options">
                <x-link-button
                    id="menuEntries"
                    label="Translations"
                    hx-get="/frame/{{$data->frame->idFrame}}/entries"
                    hx-target="#framePane"
                ></x-link-button>
                <x-link-button
                    id="menuFE"
                    label="FrameElements"
                    hx-get="/frame/{{$data->frame->idFrame}}/fes"
                    hx-target="#framePane"
                ></x-link-button>
                <x-link-button
                    id="menuNewLU"
                    label="LUs"
                    hx-get="/frame/{{$data->frame->idFrame}}/lus"
                    hx-target="#framePane"
                ></x-link-button>
                <x-link-button
                    id="menuClassification"
                    label="Classification"
                    hx-get="/frame/{{$data->frame->idFrame}}/classification"
                    hx-target="#framePane"
                ></x-link-button>
                <x-link-button
                    id="menuRelations"
                    label="Relations"
                    hx-get="/frame/{{$data->frame->idFrame}}/relations"
                    hx-target="#framePane"
                ></x-link-button>
                <x-link-button
                    id="menuFERelations"
                    label="FE-Relations"
                    hx-get="/frame/{{$data->frame->idFrame}}/fes/relations"
                    hx-target="#framePane"
                ></x-link-button>
                <x-link-button
                    id="menuSemanticTypes"
                    label="SemanticTypes"
                    hx-get="/frame/{{$data->frame->idFrame}}/semanticTypes"
                    hx-target="#framePane"
                ></x-link-button>
            </div>
        @endif
    </x-slot:nav>
    <x-slot:main>
        <div id="framePane" class="main">
            @if($data->_action == 'browse')
                @include('Structure.Frame.grid')
            @endif
            @if($data->_action == 'new')
                <x-form id="formNew" title="New Frame" center="true">
                    <x-slot:fields>
                        <x-text-field id="new_nameEn" label="English Name" value=""></x-text-field>
                    </x-slot:fields>
                    <x-slot:buttons>
                        <x-submit label="Add Frame" hx-post="/frame"></x-submit>
                    </x-slot:buttons>
                </x-form>
            @endif
        </div>
    </x-slot:main>
</x-layout.main>
