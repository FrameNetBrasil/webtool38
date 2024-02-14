<x-layout.index>
    <x-layout.browser>
        <x-slot:title>
            @include('Admin.User.title')
        </x-slot:title>
        <x-slot:search>
            @include('Admin.User.search')
        </x-slot:search>
        <x-slot:grid>
            <div id="userGrid" class="h-full p-0 w-full">
                @include('Admin.User.grid')
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
