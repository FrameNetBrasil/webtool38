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
                class="hxCol hxSpan-11-xs hxSpan-11-sm hxSpan-10-md hxOffset-1-md hxSpan-10-lg hxOffset-1-lg hxSpan-10-xl hxOffset-1-xl workArea">
                <header class="header">
                    <div class="hxRow hxGutterless">
                        <div class="hxCol hxSpan-8">
                            <h2>
                                {{$name}}
                            </h2>
                        </div>
                        <div class="hxCol hxSpan-4 actions">
                            {{$detail}}
                        </div>
                    </div>
                    <div class="hxRow hxGutterless">
                        <div class="hxCol hxSpan-8">
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
