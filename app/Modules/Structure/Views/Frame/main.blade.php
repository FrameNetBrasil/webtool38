<x-layout.index>
    <x-layout.browser>
        <x-slot:title>Frames</x-slot:title>
        <x-slot:search>
            <x-form-search id="formSearch" hx-post="/structure/frame/grid" hx-target="#gridFrame">
                <x-select id="idDomain"></x-select>
                <x-input-field id="frame"></x-input-field>
                <x-input-field id="fe" placeholder="Search FE"></x-input-field>
                <x-input-field id="lu" placeholder="Search LU"></x-input-field>
                <x-select id="listBy"></x-select>
                <x-submit label="Search"></x-submit>
                <x-button label="New Frame"></x-button>
            </x-form-search>
        </x-slot:search>
        <x-slot:grid>
            <div id="gridFrame" class="h-full p-0 w-full">
            </div>
        </x-slot:grid>
        <x-slot:footer>Footer</x-slot:footer>
    </x-layout.browser>
    <script>

        $('#idDomain').combobox({
            data: {{ Js::from($data->domain) }},
            prompt: "Select Domain",
            valueField: 'idDomain',
            textField: 'name',
        });

        $('#frame').textbox({
            prompt: 'Search Frame'
        });

        $('#fe').textbox({
            prompt: 'Search FE'
        });

        $('#lu').textbox({
            prompt: 'Search LU'
        });

        $('#listBy').combobox({
            data: [
                {listBy: 'plain list', value: ''},
                {listBy: 'by Cluster', value: 'cluster'},
                {listBy: 'by Domain', value: 'domain'},
                {listBy: 'by Type', value: 'type'},
            ],
            prompt: 'List by',
            valueField: 'value',
            textField: 'listBy',
        });

    </script>
</x-layout.index>
