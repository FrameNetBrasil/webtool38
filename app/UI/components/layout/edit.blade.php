<x-layout.index>
    <section class="wt-layout-edit">
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
                class="hxCol hxSpan-12-xs hxOffset-0-xs hxSpan-12-sm hxOffset-0-sm hxSpan-12-md hxOffset-0-md hxSpan-10-lg hxOffset-1-lg hxSpan-10-xl hxOffset-1-xl workArea">
                <header class="header">
                    <div class="hxRow hxGutterless">
                        <div class="hxCol hxSpan-12-xs hxOffset-0-xs hxSpan-12-sm hxOffset-0-sm hxSpan-6-md hxOffset-0-md hxSpan-8-lg hxOffset-0-lg hxSpan-8-xl hxOffset-0-xl">
                            <h2>
                                {{$name}}
                            </h2>
                        </div>
                        <div class="hxCol hxSpan-12-xs hxOffset-0-xs hxSpan-12-sm hxOffset-0-sm hxSpan-6-md hxOffset-0-md hxSpan-4-lg hxSpan-4-xl actions">
                            {{$detail}}
                        </div>
                    </div>
                    <div class="hxRow hxGutterless">
                        <div class="hxCol hxSpan-12">
                            {{$description}}
                        </div>
                    </div>
                </header>
                <div class="edit">
                    {{$edit}}
                </div>
            </div>
        </section>
    </section>
</x-layout.index>
