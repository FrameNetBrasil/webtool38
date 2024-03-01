<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        Alpine.data('grid', () => ({
            objects: window.annotation.loadObjects()
        }))
    })

    $(function () {
        // let annotationGridObjects = {
        //     objects: []
        // }


        // let objects = Alpine.reactive([
        //     {a: 1, b:2},
        //     {a: 3, b:4},
        // ])
        //let objects = window.annotation.loadObjects();

        $('#gridPanel').panel({
            border: false,
            fit: true
        });

        $('#gridTabs').tabs({
            border: true,
        });
    })
</script>

@php
$tabs = ['tab1','tab2','tab3'];
$slots = ['maincontent','tab-gallery'];

@endphp

<div id="gridPanel">
    <x-tabs id="test-tabs" :tabs="['Objects','Sentences']" :slots="['maincontent','tabgallery']">
        <x-slot name="maincontent">
            // some content
            aaa
        </x-slot>
        <x-slot name="tabgallery">
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
