<x-layout.content>
    <div class="wt-layout-main flex flex-column sm:col-11 md:col-11 lg:col-11 xl:col-8 mx-auto p-0 h-full">
        <article class="card flex flex-column">
            <div class="flex-none edit">
                {{$edit}}
            </div>
            <div class="nav">
                {{$nav}}
            </div>
            <section class="flex-grow-1 main">
                <div style="height:100%">
                    {{$main}}
                </div>
            </section>
        </article>
    </div>
</x-layout.content>
