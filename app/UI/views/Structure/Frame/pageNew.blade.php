<x-layout.index>
    <x-layout.edit>
        <x-slot:title>
            New Frame
        </x-slot:title>
        <x-slot:menu>

        </x-slot:menu>
        <x-slot:pane>
            <x-form id="frameFormNew" title="New Frame" center="true">
                <x-slot:fields>
                    <x-text-field id="new_nameEn" label="English Name" value=""></x-text-field>
                </x-slot:fields>
                <x-slot:buttons>
                    <x-submit label="Add Frame" hx-post="/frames"></x-submit>
                </x-slot:buttons>
            </x-form>
        </x-slot:pane>
        <x-slot:footer></x-slot:footer>
    </x-layout.edit>
</x-layout.index>
