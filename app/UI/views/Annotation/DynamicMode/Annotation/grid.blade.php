<script type="text/javascript">
    // document.addEventListener('alpine:init', () => {
    //     Alpine.data('grid', () => ({
    //         objects: window.annotation.loadObjects()
    //     }))
    // })

    $(function () {

        @include("Annotation.DynamicMode.Annotation.gridObjects")
        // let annotationGridObjects = {
        //     objects: []
        // }


        // let objects = Alpine.reactive([
        //     {a: 1, b:2},
        //     {a: 3, b:4},
        // ])
        //let objects = window.annotation.loadObjects();

        // $('#dynamicModeGrid').panel({
        //     border: false,
        //     fit: true
        // });
        //
        // $('#containerTableObjects').panel({
        //     border: false,
        //     fit: true
        // });

    })
</script>

<div id="dynamicModeGrid">
    <x-tabs
        id="dynamicModeTabs"
        :tabs="['tab1'=>'Objects','tab2'=>'Sentences']"
        :slots="['tab1' => 'objects', 'tab2' => 'sentences']"
    >
        <x-slot name="objects">
            <div id="containerTableObjects">
            <table id="gridObjects" style="height:500px">
            </table>
            </div>
            <!--
            <div id="containerTableObjects">
                <table>
                    <thead>
                    <tr>
                        <th style="width:24px"></th>
                        <th style="width:32px;text-align:right">#</th>
                        <th style="width:128px;text-align:right">Start Frame[Time]</th>
                        <th style="width:128px;text-align:right">End Frame[Time]</th>
                        <th style="width:224px;text-align:left">FN Frame.FE</th>
                        <th style="width:224px;text-align:left">CV Name (FN LU)</th>
                        <th style="width:56px;text-align:left">Origin</th>
                    </tr>
                    </thead>
                    <tbody x-data="grid" class="tbody">
                    <template x-for="object in objects">
                        <tr>
                            <td class="wt-datagrid-action" style="width:24px">
                                    <span
                                        class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                                        title="delete Document"
                                        hx-delete="/annotation/dynamicMode/object/1"
                                    ></span>
                            </td>
                            <td style="width:32px;text-align:right">
                                <span x-text="object.order"></span>
                            </td>
                            <td style="width:128px;text-align:right">
                                <span x-text="object.startFrame"></span> [<span x-text="object.startTime"></span>s]
                            </td>
                            <td style="width:128px;text-align:right">
                                <span x-text="object.endFrame"></span> [<span x-text="object.endTime"></span>s]
                            </td>
                            <td style="width:224px;max-width:224px;text-align:left">
                                <span x-text="object.frame"></span>.<span x-text="object.fe"></span>
                            </td>
                            <td style="width:224px;text-align:left">
                                <span x-text="object.lu"></span>
                            </td>
                            <td style="width:56px;text-align:left">
                                <span x-text="object.origin"></span>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>

            </div>
            -->
        </x-slot>
        <x-slot name="sentences">
            // different content
            bbb
        </x-slot>
    </x-tabs>
    <!--

<div
id="gridTabs"
>
<div class="gridTab" title="Objects" style="background-color: blue">
                <table>
                    <thead>
                    <tr>
                        <th style="width:24px"></th>
                        <th style="width:32px;text-align:right">#</th>
                        <th style="width:128px;text-align:right">Start Frame[Time]</th>
                        <th style="width:128px;text-align:right">End Frame[Time]</th>
                        <th style="width:224px;text-align:left">FN Frame.FE</th>
                        <th style="width:224px;text-align:left">CV Name (FN LU)</th>
                        <th style="width:56px;text-align:left">Origin</th>
                    </tr>
                    </thead>
                    <tbody x-data="grid" class="tbody">
                    <template x-for="object in objects">
                        <tr>
                            <td class="wt-datagrid-action" style="width:24px">
                                    <span
                                        class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                                        title="delete Document"
                                        hx-delete="/annotation/dynamicMode/object/1"
                                    ></span>
                            </td>
                            <td style="width:32px;text-align:right">
                                <span x-text="object.order"></span>
                            </td>
                            <td style="width:128px;text-align:right">
                                <span x-text="object.startFrame"></span> [<span x-text="object.startTime"></span>s]
                            </td>
                            <td style="width:128px;text-align:right">
                                <span x-text="object.endFrame"></span> [<span x-text="object.endTime"></span>s]
                            </td>
                            <td style="width:224px;text-align:left">
                                <span x-text="object.frame"></span>.<span x-text="object.fe"></span>
                            </td>
                            <td style="width:224px;text-align:left">
                                <span x-text="object.lu"></span>
                            </td>
                            <td style="width:56px;text-align:left">
                                <span x-text="object.origin"></span>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
        </div>
        <div title="Sentences">
            tab2
        </div>

    </div>
                -->
</div>
