@php
    $panelWestWidth = $data->imageMM->width + 30;
    $panelImageHeight = $data->imageMM->height + 40;
@endphp
<x-layout.index>
    @include('Annotation.StaticFrameMode2.Panes.annotation')
    <div id="staticFrameMode2AnnotationPane" class="staticFrameModeAnnotationPane">
        <div id="annotationImagePaneTitle"
             data-options="region:'north', collapsible:false, title:'Annotation: Static - Frame Mode 2', border:0,tools:'#panelTool'">
        </div>
        <div data-options="region:'west',border:0" style="padding:8px;width:{{$panelWestWidth}}px;">
            <div style="display:flex; flex-direction: column;">
                <div id="annotationImagePaneImage" style="width:100%;height:{{$panelImageHeight}}px">
                    @include('Annotation.StaticFrameMode2.Panes.imagePane')
                </div>
                <!--
                <div id="annotationImagePaneSentence" style="width:100%;">
                    @include('Annotation.StaticFrameMode2.Panes.sentencePane')
                </div>
                -->
            </div>
        </div>
        <div data-options="region:'center',border:0" style="padding:8px;">
            <div style="display:flex; flex-direction: column;">
                <div class="framePaneHeader">
                    <div>Corpus: {{$data->corpus->name}}</div>
                    <div>Document: {{$data->document->name}}</div>
                    <div>Image: {{$data->imageMM->name}}</div>
                    <div>#idStaticSentenceMM: {{$data->idStaticSentenceMM}}</div>
                </div>
                <div id="annotationImagePaneFrame">
                    @include('Annotation.StaticFrameMode2.Panes.framePane')
                </div>
            </div>
        </div>
    </div>
    <div id="annotationPaneDialog" class="easyui-panel" data-options="border:0" style="width:0;height:0"></div>
    @include('Annotation.StaticFrameMode2.Panes.navigationPane')

    <script type="text/javascript">
        $(function () {
            $('#staticFrameMode2AnnotationPane').layout({
                fit: true,
                border: true,
            })
        })
    </script>

</x-layout.index>
