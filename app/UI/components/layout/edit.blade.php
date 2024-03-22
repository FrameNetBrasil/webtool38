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
        <section id="editMaster">
            {{$master}}
        </section>
        <section id="editDetail">
            {{$detail}}
        </section>
    </section>
</x-layout.index>
