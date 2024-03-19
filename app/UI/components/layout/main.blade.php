<x-layout.index>
    <header class="main-header">
        <div class="hxRow hxGutterless">
            <div class="hxCol hxSpan-8">
                <h1>
                    {{$title}}
                </h1>
            </div>
            <div class="hxCol hxSpan-4 actions">
                {{$actions}}
            </div>
        </div>
    </header>
{{--    <div class="flex flex-column sm:col-11 md:col-11 lg:col-11 xl:col-11 mx-auto p-0 wt-layout-main">--}}
{{--        {{$slot}}--}}
{{--    </div>--}}
    <div class="hxRow">
        <div class="hxCol hxSpan-11-xs hxSpan-11-sm hxSpan-10-md hxSpan-9-lg hxSpan-6-xl mx-auto">
            {{$slot}}
        </div>
    </div>
</x-layout.index>

