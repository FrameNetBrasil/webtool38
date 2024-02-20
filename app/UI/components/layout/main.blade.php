<x-layout.index>
    <div class="wt-layout-main flex flex-column sm:col-11 md:col-11 lg:col-11 xl:col-8 mx-auto p-0 h-full">
        <article class="card flex flex-column">
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
            <div class="flex-none edit">
                {{$edit}}
            </div>
            <div class="nav">
                {{$nav}}
            </div>
            <section class="flex-grow-1">
                <div class="main">
                    {{$main}}
                </div>
            </section>
        </article>
    </div>
</x-layout.index>

