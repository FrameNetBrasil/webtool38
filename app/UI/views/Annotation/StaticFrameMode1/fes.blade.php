@php
    $sentence = trim($data->sentence->text);
    $words = explode(' ', $sentence);
    $title = "";//($data->idFrame ? "Frame: " . $data->frame->name : "No Frame")
@endphp
<x-layout.content>
    <div id="annotationStaticFrameMode1FETabs">
        @if(count($data->frames) > 0)

            @foreach($data->frames as $idFrame => $frame)
                @php($idObject = 0)
                <div title="{{$frame['name']}}" data-options="tools:'#tabTools_{{$idFrame}}'">
                    <form>
                        @foreach($data->objects as $i => $object)
                            @php($phrase = '')
                            @for($w = $object['startWord'] - 1; $w < $object['endWord']; $w++)
                                @php($phrase .= ' '. $words[$w])
                            @endfor
                            @if((count($object['bboxes']) > 0) || ($object['name'] == 'scene'))
                            <x-card class="m-2" title="Object #{{++$idObject}}: <span class='wt-anno-box-color-{{$idObject}}'>{{$phrase}}</span> ">
                                    @php($value = isset($frame['objects'][$i]) ? $frame['objects'][$i]['idFrameElement'] : null)
                                    <x-combobox.fe-frame
                                            id="idStaticObjectSentenceMM_{{$idFrame}}_{{$object['idStaticObjectSentenceMM']}}"
                                            label=""
                                            :value="$value"
                                            :idFrame="$idFrame"
                                            :hasNull="true"
                                            style="width:250px"
                                    ></x-combobox.fe-frame>
                                </x-card>
                            @else
                                @php(++$idObject)
                            @endif
                        @endforeach
                        <hr>
                        <x-button class="ml-2 mb-2" id="btnSubmitFE{{$i}}" label="Submit FEs"
                                  hx-put="/annotation/staticFrameMode1/fes/{{$data->idStaticSentenceMM}}/{{$idFrame}}"></x-button>
                    </form>
                </div>
            @endforeach
        @else
            <div title="No Frame">
            </div>
        @endif
        <script>
            $(function () {
                $('#annotationStaticFrameMode1FETabs').tabs({
                    border: true,
                    // fit:true
                    width:'100%'
                });
                @if(isset($data->idFrame))
                console.log('selecting')
                $('#annotationStaticFrameMode1FETabs').tabs('select', '{{$data->frames[$data->idFrame]['name']}}')
                @endif
            })
        </script>
    </div>
    @if(count($data->frames) > 0)
        @foreach($data->frames as $idFrame => $frame)
            <div id="tabTools_{{$idFrame}}">
                <a
                        href="javascript:void(0)"
                        class="easyui-tooltip material-icons-outlined wt-icon wt-icon-delete"
                        title="delete frame"
                        hx-delete="/annotation/staticFrameMode1/fes/{{$data->idStaticSentenceMM}}/{{$idFrame}}"
                        hx-target="body"
                ></a>
            </div>
        @endforeach
    @endif

</x-layout.content>
