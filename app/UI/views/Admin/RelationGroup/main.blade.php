<x-layout.main>
    <x-slot:title>
        Relation Group
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="primary" href="/relationgroup"></x-button>
        <x-button label="New" color="secondary" href="/relationgroup/new"></x-button>
    </x-slot:actions>
    <x-slot:edit>
        @if($data->_action == 'edit')
            <div class="grid grid-nogutter">
                <div class="col-8 title">
                    <span class="color_generic">{{$data->relationGroup?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    <x-tag label="#{{$data->relationGroup->idRelationGroup}}"></x-tag>
                    <x-button
                        label="Delete"
                        color="danger"
                        onclick="manager.confirmDelete(`Removing RelationGroup '{{$data->relationGroup?->name}}'. Confirm?`, '/relationgroup/{{$data->relationGroup->idRelationGroup}}')"
                    ></x-button>
                </div>
            </div>
            <div class="description">{{$data->relationGroup?->description}}</div>
        @endif
    </x-slot:edit>
    <x-slot:nav>
        @if($data->_action == 'browse')
            <x-form-search id="rgSearch">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <x-input-field id="search_relationGroup" :value="$data->search->relationGroup ?? ''"
                               placeholder="Search RelationGroup"></x-input-field>
                <x-input-field id="search_relationType" :value="$data->search->relationType ?? ''"
                               placeholder="Search RelationType"></x-input-field>
                <x-submit label="Search" hx-post="/relationgroup/grid" hx-target="#rgPane"></x-submit>
            </x-form-search>
        @endif
        @if($data->_action == 'edit')
            <div class="options">
                <x-link-button
                    id="menuEntries"
                    label="Translations"
                    hx-get="/relationgroup/{{$data->relationGroup->idRelationGroup}}/entries"
                    hx-target="#rgPane"
                ></x-link-button>
                <x-link-button
                    id="menuRT"
                    label="RelationTypes"
                    hx-get="/relationgroup/{{$data->relationGroup->idRelationGroup}}/rts"
                    hx-target="#rgPane"
                ></x-link-button>
            </div>
        @endif
    </x-slot:nav>
    <x-slot:main>
        <div id="rgPane" class="main">
            @if($data->_action == 'browse')
                @include('Admin.RelationGroup.grid')
            @endif
            @if($data->_action == 'new')
                <x-form id="formNew" title="New RelationGroup" center="true">
                    <x-slot:fields>
                        <x-text-field id="new_nameEn" label="English Name" value=""></x-text-field>
                    </x-slot:fields>
                    <x-slot:buttons>
                        <x-submit label="Add RelationGroup" hx-post="/relationgroup"></x-submit>
                    </x-slot:buttons>
                </x-form>
            @endif
        </div>
    </x-slot:main>
</x-layout.main>
