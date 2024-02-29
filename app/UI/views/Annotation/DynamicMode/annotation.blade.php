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
        @include("Annotation.DynamicMode.Annotation.video")
    </x-slot:video>
    <x-slot:grid>
        @include("Annotation.DynamicMode.Annotation.grid")
    </x-slot:grid>
    <x-slot:script>
        <script type="text/javascript">
            let annotationData = {
                document: {{ Js::from($document) }},
                documentMM: {{ Js::from($documentMM) }},
            }
            $(function () {
                @include("Annotation.DynamicMode.Scripts.data")

            })
        </script>
    </x-slot:script>
</x-layout.dynamic-annotation>
