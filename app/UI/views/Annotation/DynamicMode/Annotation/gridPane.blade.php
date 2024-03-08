<div id="dynamicModeGrid">
    <x-tabs
        id="dynamicModeTabs"
        :tabs="['tabObjects'=>'Objects','tabSentences'=>'Sentences']"
        :slots="['tabObjects' => 'objects', 'tabSentences' => 'sentences']"
        onSelect="{!! 'annotation.gridSentences.onSelectGrid' !!}"
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
