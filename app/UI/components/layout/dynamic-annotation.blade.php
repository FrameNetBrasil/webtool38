<x-layout.index>
    <div class="wt-layout-dynamic-annotation card flex flex-column p-0 h-full w-full">
        <header class="flex-none">
            <div class="grid grid-nogutter header">
                <div class="col-8 title">
                    {{$title}}
                </div>
                <div class="col-4 text-right">
                    {{$actions}}
                </div>
            </div>
        </header>
        <div class="flex-none">
            <div class="meta">
                {{$meta}}
            </div>
        </div>
        <section class="flex-grow-1">
            <div class="main flex flex-row p-0 h-full">
                <div class="left flex flex-column p-0 h-full">
                    <div class="video">
                        {{$video}}
                    </div>
                    <div class="controls">

                    </div>
                </div>
                <div class="right flex flex-column p-0 h-full flex-grow-1">
                    <div class="gridObjectSentence">
                        {{$grid}}
                    </div>
                    <div class="object">

                    </div>
                </div>
            </div>
        </section>
    </div>
    {{$script}}
</x-layout.index>

