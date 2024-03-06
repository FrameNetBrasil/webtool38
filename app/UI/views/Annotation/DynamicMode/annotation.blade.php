<x-layout.dynamic-annotation>
    <x-slot:title>
        Annotation: Dynamic Mode
    </x-slot:title>
    <x-slot:actions>
    </x-slot:actions>
    <x-slot:meta>
        Corpus: {{$document->corpus->name}} | Document: {{$document->name}}
    </x-slot:meta>
    <x-slot:video>
        @include("Annotation.DynamicMode.Annotation.videoPane")
    </x-slot:video>
    <x-slot:controls>
        @include("Annotation.DynamicMode.Annotation.controlsPane")
    </x-slot:controls>
    <x-slot:grid>
        @include("Annotation.DynamicMode.Annotation.gridPane")
    </x-slot:grid>
    <x-slot:script>
        <script type="text/javascript" src="/scripts/vatic/dist/compatibility.js"></script>
        <script type="text/javascript" src="/scripts/vatic/dist/jszip.js"></script>
        <script type="text/javascript" src="/scripts/vatic/dist/StreamSaver.js"></script>
        <script type="text/javascript" src="/scripts/vatic/dist/polyfill.js"></script>
        <script type="text/javascript" src="/scripts/vatic/dist/jsfeat.js"></script>
        <script type="text/javascript" src="/scripts/vatic/dist/nudged.js"></script>
        <script type="text/javascript" src="/scripts/vatic/dist/pouchdb.min.js"></script>
        <script type="text/javascript" src="/scripts/vatic/vatic.js"></script>
        <script type="text/javascript" src="/scripts/vatic/FramesManager.js"></script>
        <script type="text/javascript" src="/scripts/vatic/OpticalFlow.js"></script>
        <script type="text/javascript" src="/scripts/vatic/BoundingBox.js"></script>
        <script type="text/javascript" src="/scripts/vatic/Frame.js"></script>
        <script type="text/javascript" src="/scripts/vatic/DynamicObject.js"></script>
        <script type="text/javascript" src="/scripts/vatic/ObjectsTracker.js"></script>
        <script type="text/javascript">
            window.annotation = {
                _token: "{{ csrf_token() }}",
                document: {{ Js::from($document) }},
                documentMM: {{ Js::from($documentMM) }},
                objectList: [],
            }
            @include("Annotation.DynamicMode.Scripts.api")
            @include("Annotation.DynamicMode.Scripts.video")
            @include("Annotation.DynamicMode.Scripts.drawBox")
            @include("Annotation.DynamicMode.Scripts.objects")
            @include("Annotation.DynamicMode.Scripts.gridObjects")
            @include("Annotation.DynamicMode.Scripts.store")


            $(function () {


            })
        </script>
    </x-slot:script>
</x-layout.dynamic-annotation>
