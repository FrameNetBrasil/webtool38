<table id="mainGridTable">
</table>
<script>
    $(function () {
        $('#mainGridTable').treegrid({
            fit: true,
            url: "/lemma/listForTree",
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
                    width: '100%',
                    formatter: (value, rowData) => {
                        if (rowData.type === 'lemma') {
                            return value;
                        }
                        if (rowData.type === 'lexeme') {
                            return value;
                        }
                    }
                },
            ]],
            onClickRow: (row) => {
                if (row.type === 'lemma') {
                    let idLemma = row.id.substring(1);
                    window.location.href = `/lemma/${idLemma}/main`;
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
