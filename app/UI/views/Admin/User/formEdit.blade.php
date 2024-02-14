<x-layout.content>
    <x-form id="usersFormEdit" title="Edit" center="true">
        <x-slot:fields>
            <x-text-field
                label="Login"
                id="login"
                :value="$data->user->login"
            ></x-text-field>
            <x-text-field
                label="Name"
                id="name"
                :value="$data->user->name"
            ></x-text-field>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Save" hx-put="/users/{{$data->user->idUser}}"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
