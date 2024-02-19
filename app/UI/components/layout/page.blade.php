<x-layout.index>
    <article
        class="flex flex-column sm:col-11 md:col-11 lg:col-11 xl:col-8 mx-auto h-full p-0 wt-layout-page">
        <header class="header flex-none">
            {{$header}}
        </header>
        <div class="flex-none">
        {{$nav}}
        </div>
        <div class="flex-grow-1">
            <section class="main">
                {{$main}}
            </section>
        </div>
    </article>
</x-layout.index>

