@extends('Annotation.StaticFrameMode2.main')
@section('content')
    <x-layout.browser>
        <x-slot:nav>
            @include('Annotation.StaticFrameMode2.search')
        </x-slot:nav>
        <x-slot:main>
            <div id="staticMode2Grid" class="mainGrid">
                @include('Annotation.StaticFrameMode2.grid')
            </div>
        </x-slot:main>
    </x-layout.browser>
@endsection
