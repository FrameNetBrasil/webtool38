<x-layout.content>
    <x-form id="formFramalType" title="Type" center="true">
        <x-slot:fields>
            <div style="height:300px; overflow:auto">
                <x-checkbox.framal-type id="framalType" :idFrame="$idFrame" label=""></x-checkbox.framal-type>
            </div>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Update Type" hx-post="/frame/{{$idFrame}}/classification/type"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
