<div id="panelTool">
    <div class="navigationPane">
        @if($data->idStaticSentenceMMPrevious)
            <div class="navigationPane-previous">
                <span class="material-icons-outlined">arrow_back</span>
                <a href="/annotation/staticFrameMode1/sentence/{{$data->idStaticSentenceMMPrevious}}"><span>Previous</span></a>
            </div>
        @endif
        @if($data->idStaticSentenceMMNext)
            <div class="navigationPane-next">
                <a href="/annotation/staticFrameMode1/sentence/{{$data->idStaticSentenceMMNext}}">Next</a>
                <span class="material-icons-outlined">arrow_forward</span>
            </div>
        @endif
    </div>
</div>
