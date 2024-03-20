<x-layout.index>
    <section class="wt-layout-browse">
        <header>
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
        <section class="hxRow hxGutterless main">
            <div
                class="hxCol hxSpan-11-xs hxSpan-11-sm hxSpan-10-md hxOffset-1-md hxSpan-10-lg hxOffset-1-lg hxSpan-10-xl hxOffset-1-xl workArea">
                <div class="search">
                    {{$search}}
                </div>
                <div class="grid">
                    {{$grid}}
                </div>
            </div>
        </section>
    </section>
</x-layout.index>

