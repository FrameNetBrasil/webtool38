<x-layout.main>
    <x-slot:title>
        Relation Group
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="primary" href="/relationgroup"></x-button>
        <x-button label="New" color="secondary" href="/relationgroup/new"></x-button>
    </x-slot:actions>
    @yield('content')
</x-layout.main>
