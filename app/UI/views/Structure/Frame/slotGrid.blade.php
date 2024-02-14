<table id="frameSlotGridTable">
</table>
<script>
    $(function () {
        $('#frameSlotGridTable').treegrid({
            fit: true,
            url: "/frames/listForTree",
            queryParams: {{ Js::from($data->search) }},
            showHeader: false,
            rownumbers: false,
            idField: 'id',
            treeField: 'name',
            border: false,
            columns: [[
                {
                    field: 'name',
                    formatter: (value, rowData) => {
                        if (rowData.type === 'frame') {
                            return `<div><div class='color-frame'>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                        }
                        if (rowData.type === 'fe') {
                            return `<div><div><span class='fe-name color_${rowData.idColor}'>${value[0]}</span></div><div class='definition'>${value[1]}</div></div>`;
                        }
                        if (rowData.type === 'lu') {
                            return `<div><div class='color-lu'>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                        }
                    }
                },
            ]],
            onClickRow: (row) => {
                if (row.type === 'frame') {
                    let idFrame = row.id.substring(1);
                    window.location.href = `/frames/${idFrame}/edit`;
                }
            },
        });
    });
</script>
<style>
    .definition {
        display: inline-block;
        font-size: 12px;
    }

    .fe-name {
        display: inline-block;
        font-size: 12px;
    }

    .datagrid-body table tbody tr td div.datagrid-cell {
        height: 40px !important;
        padding-top: 4px;
    }
</style>
