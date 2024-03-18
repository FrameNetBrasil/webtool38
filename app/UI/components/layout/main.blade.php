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
    <div class="flex flex-column sm:col-11 md:col-11 lg:col-11 xl:col-11 mx-auto p-0 wt-layout-main">
        {{$slot}}
    </div>
</x-layout.index>

