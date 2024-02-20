<x-layout.index>
    <article
        class="flex flex-column sm:col-11 md:col-11 lg:col-11 xl:col-8 mx-auto h-full p-0 wt-layout-main">
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
        <div class="body flex flex-column h-full flex-grow-1">
            <div class="flex-none">
                {{$edit}}
            </div>
            <div class="flex-none">
                {{$nav}}
            </div>
            <section class="main flex-grow-1">
                {{$main}}
            </section>
        </div>
    </article>
</x-layout.index>

