<x-dynamic-component component="layout.{{$_layout ?? 'detail'}}">
    <x-slot:title>
        Relation Type
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="primary" href="/relationgroup"></x-button>
        <x-button label="New" color="secondary" href="/relationtype/new"></x-button>
    </x-slot:actions>
    @yield('content')
</x-dynamic-component>
