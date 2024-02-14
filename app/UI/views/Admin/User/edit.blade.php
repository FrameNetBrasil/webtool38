<x-layout.index>
    <x-layout.edit>
        <x-slot:title>
            @include('Admin.User.title')
        </x-slot:title>
        <x-slot:menu>
            @include('Admin.User.menu')
        </x-slot:menu>
        <x-slot:pane>
            <div id="usersPane">
            </div>
        </x-slot:pane>
        <x-slot:footer></x-slot:footer>
    </x-layout.edit>
</x-layout.index>
