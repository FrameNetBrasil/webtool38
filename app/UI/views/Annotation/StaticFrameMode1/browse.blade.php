@extends('Annotation.StaticFrameMode1.main')
@section('content')
    <x-layout.browser>
        <x-slot:nav>
            @include('Annotation.StaticFrameMode1.search')
        </x-slot:nav>
        <x-slot:main>
            <div id="staticMode1Grid" class="mainGrid">
                @include('Annotation.StaticFrameMode1.grid')
            </div>
        </x-slot:main>
    </x-layout.browser>
@endsection
