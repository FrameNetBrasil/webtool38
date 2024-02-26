<x-layout.index>
    <div class="wt-container-center desktop-only">
        <div class="wt-container-center-content">
            <x-form id="formLogin" title="Login" center="true" >
                <x-slot:fields>
                    <img src="/images/fnbr_logo.png"/>
                    <x-text-field id="login" label="Login" value=""></x-text-field>
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
