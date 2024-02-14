<x-layout.index>
    <x-layout.report>
        <x-slot:menu>
            <div class="text-right">
                <x-button id="btnDownload" label="Save as PDF" color="secondary" class="m-1"></x-button>
                <x-button id="btnBack" label="Return to search" color="secondary" hx-get="/report/frames"
                          hx-target="body"
                          class="m-1"></x-button>
            </div>
        </x-slot:menu>
        <x-slot:pane>
            <div id="luReport">
                <div class="grid">
                    <div class="col-6 title">
                        {{$data->report['lu']->name}}
                        <x-link-button
                                id="btnFrame"
                                href="/report/frames/{{$data->report['lu']->frame->idFrame}}"
                                icon="frame__before"
                                label="{{$data->report['lu']->frame->name}}"
                                plain="false"
                                class="mb-1"
                        ></x-link-button>
                    </div>
                    <div class="col-6 text-right">
                        [Language: {{$data->report['lu']->language->language}}][#{{$data->report['lu']->idLU}}]
                    </div>
                </div>
                <x-card title="Definition" class="luReport__card">
                    {!! $data->report['lu']->senseDescription !!}
                </x-card>
                <x-card title="Frame Elements" class="luReport__card">
                </x-card>
                <x-card title="Frame-Frame Relations" class="luReport__card">
                </x-card>
                <x-card title="Lexical Units" class="luReport__card">
                </x-card>
            </div>
        </x-slot:pane>
    </x-layout.report>
    <script>
        const options = {
            margin: 0.5,
            filename: '{{$data->report['lu']->name}}.pdf',
            image: {
                type: 'jpeg',
                quality: 500
            },
            html2canvas: {
                scale: 1
            },
            jsPDF: {
                unit: 'in',
                format: 'a4',
                orientation: 'portrait'
            }
        }

        $('#btnDownload').click(function (e) {
            e.preventDefault();
            const element = document.getElementById('luReport');
            html2pdf().from(element).set(options).save();
        });

    </script>
    <style>
        .luReport__card {
            margin-bottom: 8px;
        }

        .luReport__table {
            border-spacing: 8px;
        }
    </style>
</x-layout.index>