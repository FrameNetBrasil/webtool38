<table id="mainGridTable" >
</table>
<script>
    $(function () {
        $('#mainGridTable').treegrid({
            fit: true,
            url: "/annotation/dynamicMode/listForTree",
            method: "POST",
            queryParams: {{ Js::from($search) }},
            showHeader: false,
            rownumbers: false,
            idField: 'id',
            treeField: 'name',
            showFooter:true,
            border: false,
            striped: true,
            columns: [[
                {
                    field: 'name',
                    width: '100%',
                    formatter: (value, rowData) => {
                        if (rowData.type === 'corpus') {
                            return `<div><div class='color-corpus'>${value[0]}</div></div>`;
                        }
                        if (rowData.type === 'document') {
                            return `<div><div class='color-document'>${value}</div></div>`;
                        }
                        if (rowData.type === 'sentence') {
                            return `<div><div class='color-document'>${value}</div></div>`;
                        }
                    }
                },
            ]],
            onClickRow: (row) => {
                if (row.type === 'document') {
                    let idDocument = row.id.substring(1);
                    window.location.href = `/annotation/dynamicMode/${idDocument}`;
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
        height: 24px !important;
        padding-top: 4px;
    }
</style>
