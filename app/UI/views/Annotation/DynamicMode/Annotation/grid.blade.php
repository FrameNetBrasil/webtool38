<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        Alpine.data('grid', () => ({
            objects: [
                {a: 1, b: 2},
                {a: 3, b: 4},
            ]
        }))
    })

        $(function () {
        let annotationGridObjects = {
            objects: []
        }


        let objects = Alpine.reactive([
            {a: 1, b:2},
            {a: 3, b:4},
        ])


        $('#gridPanel').panel({
            border: false,
            fit: true
        });

        $('#gridTabs').tabs({
            border: true,
        });
    })
</script>

<div id="gridPanel">
    <div
        id="gridTabs"
        style="width:100% !important"
    >

        <div title="Objects">
            <div
                class="wt-datagrid"
                style="padding: 16px"
            >
                <table>
                    <thead>
                    <tr>
                        <th>x</th>
                        <th>y</th>
                    </tr>
                    </thead>
                    <tbody x-data="grid">
                    <template x-for="object of objects">
                        <tr>
                            <td class="wt-datagrid-action">
                <span
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete Document"
                    hx-delete="/annotation/dynamicMode/object/1"
                ></span>
                            </td>
                            <td
                            >
                                <span x-text="object.a"></span>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
        </div>
        <div title="Sentences">
            tab2
        </div>

    </div>
</div>
