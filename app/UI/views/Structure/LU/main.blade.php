<x-layout.main>
    <x-slot:title>
        LU
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="primary" href="/lu"></x-button>
    </x-slot:actions>
    @yield('content')
</x-layout.main>
