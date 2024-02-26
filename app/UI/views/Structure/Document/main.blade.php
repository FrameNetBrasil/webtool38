<x-dynamic-component component="layout.{{$_layout ?? 'detail'}}">
    <x-slot:title>
        Document
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="primary" href="/corpus"></x-button>
    </x-slot:actions>
    @yield('content')
</x-dynamic-component>
