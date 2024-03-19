<x-layout.index>
    <header>
        <h1>
            Webtool
        </h1>
        <p>
            Webtool is an annotation and database management application developed by FrameNet Brasil Lab. Webtool handles multilingual framenets and constructicons.
        </p>
    </header>
    <div class="hxRow hxGutterless">
        <div class="hxCol hxSpan-12-xs hxSpan-6-sm hxSpan-4-md hxSpan-3-lg hxSpan-2-xl mx-auto">
            <x-form id="formLogin" title="Login" center="true" >
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
                    <x-submit label="Login" hx-post="/login" ></x-submit>
                </x-slot:buttons>
            </x-form>
        </div>
    </div>
</x-layout.index>
<script>
    $(function () {
        $("#formLogin").on('htmx:beforeRequest', event => {
            let p = event.detail.requestConfig.parameters.password;
            event.detail.requestConfig.parameters.password = md5(p);
        })
    });
</script>
