<x-layout.index>
    <script type="text/javascript">
        let idSentence = {{$data->idSentence}};
        let csrf = '{{csrf_token()}}';
        let annotationData = {{ Js::from($data) }};
        console.log(annotationData);
        @include('Annotation.Corpus.Layers.annotation')

        $(function () {

            annotation.initDatagrid();
            annotation.$dg = $('#dataGridLayers');

            $('#corpusAnnotationPane').panel({
                fit: true,
                border: false
            })

            $('.itemMenu').click((e => {
                    annotation.onLabelClick($(e.currentTarget).data().idlabeltype);
                })
            )

            $('.itemMenuLU').click((e => {
                    annotation.onLUClick($(e.currentTarget).data().idlu);
                })
            )

            $('.itemMenuNI').click((e => {
                    annotation.onNIClick($(e.currentTarget).data().idlabeltype);
                })
            )

            // $(window).on('addx', () => console.log('a'));

        })
    </script>
    <div id="corpusAnnotationPane" style="padding:0 8px 8px 8px">
        <table id="dataGridLayers">
        </table>
        @include('Annotation.Corpus.Layers.markup')
    </div>
    <div id="annotationPaneDialog" class="easyui-panel" data-options="border:0" style="width:0;height:0"></div>
    <div
            style="display:none"
    >
        @foreach($data->layerLabels as $entry => $idLabels)
            @if($entry == 'lty_fe')
                @foreach($idLabels as $idAnnotationSet => $idFELabels)
                    <div id="menu_{{$entry}}_{{$idAnnotationSet}}" class="corpusAnnotationMenu menu-content">
                        <div class="container">
                            <div class="itemMenu" data-idlabeltype="0">
                                <x-icon icon="delete">Clear</x-icon>
                            </div>
                            @foreach($idFELabels as $idLabel)
                                <div
                                        class="itemMenu color_{{$data->labelTypes[$idLabel]['idColor']}}"
                                        data-idlabeltype="{{$idLabel}}"
                                >
                                    <x-icon icon="fe-{{$data->labelTypes[$idLabel]['coreType']}}">
                                        <span>{{$data->labelTypes[$idLabel]['label']}}</span>
                                    </x-icon>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @push('onload')
                        $('#menu_{{$entry}}_{{$idAnnotationSet}}').menu();
                    @endpush
                @endforeach
                @foreach($idLabels as $idAnnotationSet => $idFELabels)
                    <div
                            x-data="{x : {}}"
                            x-on:add-fe-annotated-{{$idAnnotationSet}}.window="x[$event.detail] = $event.detail; console.log(x)"
                            id="menu_{{$entry}}_{{$idAnnotationSet}}_ni"
                            class="corpusAnnotationMenu menu-content"
                    >
                        <div class="optionsNI">
                            @foreach($data->instantiationType as $it)
                                <input type="radio" name="rbNI_{{$idAnnotationSet}}"
                                       id="rbNI_{{$idAnnotationSet}}_{{$it->value}}"
                                       value="{{$it->value}}">{{$it->label}}
                            @endforeach
                        </div>
                        <div class="container"

                        >
                            @foreach($idFELabels as $idLabel)
                                @if(($data->labelTypes[$idLabel]['coreType'] == 'core') || ($data->labelTypes[$idLabel]['coreType'] == 'core-unexpressed'))
                                    <div
                                            x-show="x[{{$idLabel}}] === undefined"
                                            id="menu_ni_{{$idLabel}}"
                                            class="itemMenuNI color_{{$data->labelTypes[$idLabel]['idColor']}}"
                                            data-idlabeltype="{{$idLabel}}"
                                    >
                                        <x-icon icon="fe-{{$data->labelTypes[$idLabel]['coreType']}}">
                                                <span>{{$data->labelTypes[$idLabel]['label']}}</span>
                                        </x-icon>

                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @push('onload')
                        $('#menu_{{$entry}}_{{$idAnnotationSet}}_ni').menu();
                        $('#rbNI_{{$idAnnotationSet}}').radiogroup({
                        onChange: function(value) { console.log(value);}
                        });
                    @endpush
                @endforeach
            @else
                <div id="menu_{{$entry}}" class="corpusAnnotationMenu menu-content">
                    <div class="container">
                        <div class="itemMenu" data-idlabeltype="0">
                            <x-icon icon="delete">Clear</x-icon>
                        </div>
                        @foreach($idLabels as $idLabel)
                            <div class="itemMenu color_{{$data->labelTypes[$idLabel]['idColor']}}"
                                 data-idlabeltype="{{$idLabel}}">
                                {{$data->labelTypes[$idLabel]['label']}}
                            </div>
                        @endforeach
                    </div>
                </div>
                @push('onload')
                    $('#menu_{{$entry}}').menu();
                @endpush
            @endif
        @endforeach
        @foreach($data->lus as $word => $lus)
            <div id="menu_lty_all_targets_{{$word}}" class="corpusAnnotationMenuLU menu-content">
                <div class="rbNI"></div>
                <div class="container">
                    <div class="itemMenuLU" data-idlu="0">
                        <x-icon icon="delete">Clear selection</x-icon>
                    </div>
                    @foreach($lus as $luData)
                        <div class="itemMenuLU" data-idlu="{{$luData['idLU']}}">
                            {{$luData['frameName']}}.{{$luData['name']}}
                        </div>
                    @endforeach
                </div>
            </div>
            @push('onload')
                $('#menu_lty_all_targets_{{$word}}').menu();
            @endpush
        @endforeach
    </div>
    <style>
        .datagrid-cell .dataGridLayers_datagrid-cell-c1-layer {
            width: auto;
        }
    </style>
</x-layout.index>
