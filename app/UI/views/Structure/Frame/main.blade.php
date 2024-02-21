<x-layout.main>
    <x-slot:title>
        Frame
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="primary" href="/frame"></x-button>
        <x-button label="New" color="secondary" href="/frame/new"></x-button>
    </x-slot:actions>
    @yield('content')
</x-layout.main>
