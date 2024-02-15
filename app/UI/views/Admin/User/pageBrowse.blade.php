<x-layout.index>
    <x-layout.browser>
        <x-slot:title>
            @include('Admin.User.slotTitle')
        </x-slot:title>
        <x-slot:search>
            @include('Admin.User.slotSearch')
        </x-slot:search>
        <x-slot:grid>
            <div id="userSlotGrid" class="h-full p-0 w-full">
                @include('Admin.User.slotGrid')
            </div>
        </x-slot:grid>
        <x-slot:footer></x-slot:footer>
    </x-layout.browser>
</x-layout.index>
<script>
    @if($data->deleteSuccess ?? false)
    manager.messager('success', 'User removed.');
    @endif
</script>
