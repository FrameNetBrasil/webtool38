<x-layout.content>
    <x-form id="formFramalDomain" title="Domain" center="true">
        <x-slot:fields>
            <div style="height:300px; overflow:auto">
            <x-checkbox.framal-domain id="framalDomain" :idFrame="$idFrame" label=""></x-checkbox.framal-domain>
            </div>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Update Domain" hx-post="/frame/{{$idFrame}}/classification/domain"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
