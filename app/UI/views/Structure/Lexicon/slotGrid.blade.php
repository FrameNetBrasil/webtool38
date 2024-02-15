<table id="lexiconSlotGridTable">
</table>
<script>
    $(function () {
        $('#lexiconSlotGridTable').treegrid({
            fit: true,
            url: "/lexicon/listForTree",
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
                        if (rowData.type === 'lemma') {
                            return value;
                        }
                        if (rowData.type === 'lexeme') {
                            return `<div><div><span class='fe-name color_${rowData.idColor}'>${value[0]}</span></div><div class='definition'>${value[1]}</div></div>`;
                        }
                        if (rowData.type === 'wordform') {
                            return `<div><div class='color-lu'>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                        }
                    }
                },
            ]],
            onClickRow: (row) => {
                if (row.type === 'lemma') {
                    let idLemma = row.id.substring(1);
                    window.location.href = `/lemma/${idLemma}/edit`;
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
