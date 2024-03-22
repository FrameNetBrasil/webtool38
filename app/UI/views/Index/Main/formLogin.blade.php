<x-layout.main>
    <x-slot:header>
        <h1>
            Webtool
        </h1>
        <p>
            Webtool is an annotation and database management application developed by FrameNet Brasil Lab. Webtool
            handles multilingual framenets and constructicons.
        </p>
    </x-slot:header>
    <x-slot:main>
        <section class="hxRow hxGutterless">
            <div
                class="hxCol hxSpan-11-xs hxSpan-11-sm hxSpan-4-md hxOffset-4-md hxSpan-4-lg hxOffset-4-lg hxSpan-4-xl hxOffset-4-xl">
                <x-form id="formLogin" title="Login" center="true">
                    <x-slot:fields>
                        <div style="text-align: center">
                            <img src="/images/fnbr_logo.png"/>
                        </div>
                        <x-text-field
                            id="login"
                            label="Login"
                            value=""
                        ></x-text-field>
                        <x-password-field
                            id="password"
                            label="Password"
                        ></x-password-field>
                    </x-slot:fields>

                    <x-slot:buttons>
                        <x-submit label="Login" hx-post="/login"></x-submit>
                    </x-slot:buttons>
                </x-form>
            </div>
        </section>
    </x-slot:main>
</x-layout.main>
<script>
    $(function () {
        $("#formLogin").on('htmx:beforeRequest', event => {
            let p = event.detail.requestConfig.parameters.password;
            event.detail.requestConfig.parameters.password = md5(p);
        })
    });
</script>
