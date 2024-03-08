<table id="mainGridTable">
</table>
<script>
    $(function () {
        $('#mainGridTable').treegrid({
            fit: true,
            url: "/genre/listForGrid",
            queryParams: {{ Js::from($search) }},
            showHeader: false,
            rownumbers: false,
            idField: 'id',
            treeField: 'name',
            showFooter:true,
            border: false,
            columns: [[
                {
                    field: 'name',
                    width: "100%",
                    formatter: (value, rowData) => {
                        if (rowData.type === 'genre') {
                            return `<div><div>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                        }
                    }
                },
            ]],
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
