<x-dynamic-component component="layout.{{$data->_layout ?? 'detail'}}">
    <x-slot:title>
        LU
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="primary" href="/frame"></x-button>
        <x-button label="New" color="secondary" href="/lu/new"></x-button>
    </x-slot:actions>
    @yield('content')
</x-dynamic-component>
