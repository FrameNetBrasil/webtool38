<table id="annotationCorpusGridTable" >
</table>
<script>
    $(function () {
        $('#annotationCorpusGridTable').treegrid({
            fit: true,
            url: "/annotation/corpus/listForTree",
            method: "POST",
            queryParams: {{ Js::from($data->search) }},
            showHeader: false,
            rownumbers: false,
            idField: 'id',
            treeField: 'name',
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
                if (row.type === 'sentence') {
                    let idSentence = row.id.substring(1);
                    window.open(`/annotation/corpus/sentence/${idSentence}`, '_blank');
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