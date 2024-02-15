<x-layout.index>
    <x-layout.edit>
        <x-slot:title>
            @include('Admin.User.slotTitle')
        </x-slot:title>
        <x-slot:menu>
            @include('Admin.User.slotMenu')
        </x-slot:menu>
        <x-slot:pane>
            <div id="userEditPane">
            </div>
        </x-slot:pane>
        <x-slot:footer></x-slot:footer>
    </x-layout.edit>
</x-layout.index>
