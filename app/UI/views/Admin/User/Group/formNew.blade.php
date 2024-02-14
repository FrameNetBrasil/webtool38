<x-layout.content>
    <x-form id="usersGroupFormNew" title="New Group" center="true">
        <x-slot:fields>
            <x-combobox.group id="idGroup" label="Group"></x-combobox.group>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Save" hx-post="/users/{{$data->idUser}}/group"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
