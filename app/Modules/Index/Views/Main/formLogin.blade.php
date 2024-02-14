<x-layout.index>
    <div class="wt-container-center">
        <x-form id="frmLogin" title="Login" center="true" hx-post="/authenticate">
            <x-slot:fields>
                <div class="grid grid-cols-1 gap-6">
                    <x-text-field id="username" label="Username" placeholder="username"></x-text-field>
                    <x-password-field id="password" label="Password"></x-password-field>
                </div>
            </x-slot:fields>
            <x-slot:buttons>
                <x-submit label="Login"></x-submit>
            </x-slot:buttons>
        </x-form>
    </div>
    <script>
    </script>
</x-layout.index>
