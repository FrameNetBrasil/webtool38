<div id="dynamicModeGrid">
    <x-tabs
        id="dynamicModeTabs"
        :tabs="['tab1'=>'Objects','tab2'=>'Sentences']"
        :slots="['tab1' => 'objects', 'tab2' => 'sentences']"
    >
        <x-slot name="objects">
            <div id="containerTableObjects">
                <table id="gridObjects" style="height:500px">
                </table>
            </div>
        </x-slot>
        <x-slot name="sentences">
            <div id="containerTableSentences">
                <table id="gridSentences" style="height:500px">
                </table>
            </div>
        </x-slot>
    </x-tabs>
</div>
