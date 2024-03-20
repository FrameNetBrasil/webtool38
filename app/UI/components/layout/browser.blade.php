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
    <div class="hxRow">
        <div class="hxCol hxSpan-11-xs hxSpan-11-sm hxSpan-10-md hxSpan-9-lg hxSpan-6-xl mx-auto">
            <div class="search">
                {{$search}}
            </div>
            <div class="grid">
                {{$grid}}
            </div>
        </div>
    </div>
</x-layout.index>

