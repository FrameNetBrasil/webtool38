<x-dynamic-component component="layout.{{$data->_layout}}">
    <x-slot:header>
        @if(isset($data->frameElement))
            <div class="grid grid-nogutter">
                <div class="col-8 title">
                    <span>FE: {{$data->frameElement?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    <span>[{{$data->frameElement->frame->name}}]</span>
                    <span>[#{{$data->frameElement->idFrameElement}}]</span>
                </div>
            </div>
        @endif
    </x-slot:header>
    <x-slot:nav>
        <div class="options">
            <x-link-button
                id="menuFEEdit"
                label="Edit"
                hx-get="/fe/{{$data->frameElement->idFrameElement}}/formEdit"
                hx-target="#childEditPane"
            ></x-link-button>
            <x-link-button
                id="menuFEEntries"
                label="Translations"
                hx-get="/fe/{{$data->frameElement->idFrameElement}}/entries"
                hx-target="#childEditPane"
            ></x-link-button>
            <x-link-button
                id="menuFEConstraints"
                label="Constraints"
                hx-get="/fe/{{$data->frameElement->idFrameElement}}/constraints"
                hx-target="#childEditPane"
            ></x-link-button>
            <x-link-button
                id="menuFESemanticTypes"
                label="SemanticTypes"
                hx-get="/fe/{{$data->frameElement->idFrameElement}}/semanticTypes"
                hx-target="#childEditPane"
            ></x-link-button>
        </div>
    </x-slot:nav>
    <x-slot:main>
        <div id="childEditPane">
        </div>
    </x-slot:main>
    <x-slot:footer></x-slot:footer>
</x-dynamic-component>
