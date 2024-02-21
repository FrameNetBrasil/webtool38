<x-layout.index>
    <div class="wt-layout-main card flex flex-column sm:col-11 md:col-11 lg:col-11 xl:col-8 mx-auto p-0 h-full">
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
        {{$slot}}
    </div>
</x-layout.index>

