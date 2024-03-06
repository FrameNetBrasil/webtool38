<div id="sentencePane" style="width:100%">
    <div id="annotatedSentence"
         style="width:100%; margin-bottom:8px;display: flex; flex-direction:row; flex-wrap:wrap;" x-data>
        <template x-for="w in $store.annotation.words" :key="w.id">
            <div class="annotatedWord" :id="w.id" :class="'wt-anno-box-color-' + w.idObject" x-text="w.word"></div>
        </template>
    </div>
</div>

