<x-layout.main>
    <x-slot:title>
        Lemma
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="primary" href="/lemma"></x-button>
        <x-button label="New" color="secondary" href="/lemma/new"></x-button>
    </x-slot:actions>
    @yield('content')
</x-layout.main>
