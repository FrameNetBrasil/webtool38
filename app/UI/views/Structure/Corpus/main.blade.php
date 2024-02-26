<x-layout.main>
    <x-slot:title>
        Corpus
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="primary" href="/corpus"></x-button>
        <x-button label="New" color="secondary" href="/corpus/new"></x-button>
    </x-slot:actions>
    @yield('content')
</x-layout.main>
