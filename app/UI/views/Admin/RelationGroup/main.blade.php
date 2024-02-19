<x-layout.page>
    <x-slot:header>
        @if($data->_action == 'edit')
            <div class="grid grid-nogutter">
                <div class="col-8 title">
                    <span>RelationGroup: {{$data->relationGroup?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    <span>[#{{$data->relationGroup->idRelationGroup}}]</span>
                </div>
            </div>
            <div class="description">{{$data->relationGroup?->description}}</div>
        @else
            <span>RelationGroup</span>
        @endif
    </x-slot:header>
    <x-slot:nav>
        @if($data->_action == 'browse')
            <x-form-search id="dormSearch">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <x-input-field id="search_relationGroup" :value="$data->search->relationGroup ?? ''"
                               placeholder="Search RelationGroup"></x-input-field>
                <x-input-field id="search_relationType" :value="$data->search->relationType ?? ''"
                               placeholder="Search RelationType"></x-input-field>
                <x-submit label="Search" hx-post="/relationgroup/grid" hx-target="#mainGrid"></x-submit>
                <x-button label="New RelationGroup" color="secondary" href="/relationgroup/new"></x-button>
            </x-form-search>
        @endif
        @if($data->_action == 'edit')
            <div class="options">
                <x-link-button
                    id="menuEntries"
                    label="Translations"
                    hx-get="/relationgroup/{{$data->relationGroup->idRelationGroup}}/entries"
                    hx-target="#editPane"
                ></x-link-button>
                <x-link-button
                    id="menuRT"
                    label="RelationTypes"
                    hx-get="/relationgroup/{{$data->relationGroup->idRelationGroup}}/rts"
                    hx-target="#editPane"
                ></x-link-button>
            </div>
        @endif
    </x-slot:nav>
    <x-slot:main>
        @if($data->_action == 'browse')
            <div id="mainGrid" class="h-full p-0 w-full">
                @include('Admin.RelationGroup.grid')
            </div>
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
        @if($data->_action == 'edit')
            <div id="editPane">
            </div>
        @endif
    </x-slot:main>
</x-layout.page>
