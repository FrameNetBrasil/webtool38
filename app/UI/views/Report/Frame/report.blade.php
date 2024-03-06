<x-layout.index>
    <x-layout.report>
        <x-slot:menu>
            <div class="text-right">
                <x-button id="btnDownload" label="Save as PDF" color="secondary" class="m-1"></x-button>
                <x-button id="btnBack" label="Return to search" color="secondary" hx-get="/report/frame" hx-target="body"
                          class="m-1"></x-button>
            </div>
        </x-slot:menu>
        <x-slot:pane>
            <div id="frameReport">
                <div class="grid">
                    <div class="col-6 title">
                        {{$report['frame']->name}}
                    </div>
                    <div class="col-6 text-right">
                        @foreach ($report['classification'] as $name => $values)
                            [
                            @foreach ($values as $value)
                                {{$value}}
                            @endforeach
                            ]
                        @endforeach
                    </div>
                </div>
                <x-card title="Definition" class="frameReport__card">
                    {!! $report['frame']->description !!}
                </x-card>
                <x-card title="Frame Elements" class="frameReport__card">
                    <x-card title="Core" class="frameReport__card">
                        <table id="feNuclear" class="frameReport__table">
                            <tbody>
                            @foreach ($report['fe']['core'] as $fe)
                                <tr>
                                    <td>
                                        <span class="color_{{$fe['idColor']}}">{{$fe['name']}}</span>
                                        @foreach ($fe['relations'] as $relation)
                                            <br><b>{{$relation['name']}}:&nbsp;</b>{{$relation['relatedFEName']}}
                                        @endforeach
                                    </td>
                                    <td class="pl-2">{!! $fe['description'] !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if ($report['fe']['core_unexpressed'])
                            <x-card-plain title="Core-Unexpressed" class="frameReport__card">
                                <table id="feCoreUnexpressed" class="frameReport__table">
                                    <tbody>
                                    @foreach ($report['fe']['core_unexpressed'] as $fe)
                                        <tr>
                                            <td>
                                                <span class="color_{{$fe['idColor']}}">{{$fe['name']}}</span>
                                                @foreach ($fe['relations'] as $relation)
                                                    <br><b>{{$relation['name']}}
                                                        :&nbsp;</b>{{$relation['relatedFEName']}}
                                                @endforeach
                                            </td>
                                            <td class="pl-2">{!! $fe['description'] !!}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </x-card-plain>
                        @endif
                        @if ($report['fecoreset'])
                            <table id="feCoreSet" class="frameReport__table">
                                <tbody>
                                <tr>
                                    <td class="header">FE Core set(s):</td>
                                </tr>
                                <tr>
                                    <td class="pl-2">{{$report['fecoreset']}}</td>
                                </tr>
                                </tbody>
                            </table>
                        @endif

                    </x-card>
                    <x-card title="Non-Core">
                        <table id="feNonNuclear" class="frameReport__table">
                            <tbody>
                            @foreach ($report['fe']['noncore'] as $fe)
                                <tr>
                                    <td>
                                        <span class="color_{{$fe['idColor']}}">{{$fe['name']}}</span>
                                        @foreach ($fe['relations'] as $relation)
                                            <br><b>{{$relation['name']}}:&nbsp;</b>{{$relation['relatedFEName']}}
                                        @endforeach
                                    </td>
                                    <td class="pl-2">{!! $fe['description'] !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </x-card>
                </x-card>
                <x-card title="Frame-Frame Relations" class="frameReport__card">
                    @php($i = 0)
                    @foreach ($report['relations'] as $name => $relations)
                        <x-card-plain
                                title="{{$name}}" @class(["frameReport__card" => (++$i < count($report['relations']))])>
                            @foreach ($relations as $idFrame => $relation)
                                <x-link-button
                                        id="btnRelation{{$idFrame}}"
                                        href="/report/frame/{{$idFrame}}"
                                        icon="frame__before"
                                        label="{{$relation['name']}}"
                                        plain="false"
                                        class="mb-1"
                                ></x-link-button>
                            @endforeach
                        </x-card-plain>
                    @endforeach
                </x-card>
                <x-card title="Lexical Units" class="frameReport__card">
                    @foreach ($report['lus'] as $idLU => $lu)
                        <x-link-button
                                id="btnLU{{$idLU}}"
                                href="/report/lu/{{$idLU}}"
                                icon="lu__before"
                                label="{{$lu}}"
                                plain="false"
                                class="mb-1"
                        ></x-link-button>
                    @endforeach
                </x-card>
            </div>
        </x-slot:pane>
    </x-layout.report>
    <script>
        const options = {
            margin: 0.5,
            filename: '{{$report['frame']->name}}.pdf',
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
            const element = document.getElementById('frameReport');
            html2pdf().from(element).set(options).save();
        });

    </script>
    <style>
        .frameReport__card {
            margin-bottom: 8px;
        }

        .frameReport__table {
            border-spacing: 8px;
        }
    </style>
</x-layout.index>
