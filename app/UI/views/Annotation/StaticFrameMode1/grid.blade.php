<table id="annotationStaticFrameMode1GridTable" >
</table>
<script>
    $(function () {
        $('#annotationStaticFrameMode1GridTable').treegrid({
            fit: true,
            url: "/annotation/staticFrameMode1/listForTree",
            method: "GET",
            queryParams: {{ Js::from($search) }},
            showHeader: true,
            rownumbers: false,
            idField: 'id',
            treeField: 'name',
            border: false,
            striped: true,
            columns: [[
                {
                    field: 'name',
                    width:"60%",
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
                {
                    field: 'idSentence',
                    title: '#idSentence',
                    width:"10%",
                },
                {
                    field: 'idStaticSentenceMM',
                    title: '#idStaticSentenceMM',
                    width:"12%",
                },
                {
                    field: 'image',
                    title: 'image',
                    width:"12%",
                },
                {
                    field: 'status',
                    title: 'status',
                    width:"6%",
                    formatter: (value, rowData) => {
                        if (value !== '') {
                            if (value === 'green') {
                                return `<div class='material-icons-outlined wt-icon wt-icon-annotation-success'></div>`;
                            }
                            if (value === 'yellow') {
                                return `<div class='material-icons-outlined wt-icon wt-icon-annotation-warning'></div>`;
                            }
                            if (value === 'red') {
                                return `<div class='material-icons-outlined wt-icon wt-icon-annotation-error'></div>`;
                            }
                        }
                    }
                }
            ]],
            onClickRow: (row) => {
                if (row.type === 'sentence') {
                    let idSentence = row.id.substring(1);
                    window.open(`/annotation/staticFrameMode1/sentence/${idSentence}`, '_blank');
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
