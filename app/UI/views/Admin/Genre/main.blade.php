<x-layout.main>
    <x-slot:title>
        Genre
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="primary" href="/genre"></x-button>
        <x-button label="New" color="secondary" href="/genre/new"></x-button>
    </x-slot:actions>
    @yield('content')
</x-layout.main>
