<table id="mainGridTable">
</table>
<script>
    $(function () {
        $('#mainGridTable').treegrid({
            fit: true,
            url: "/relationgroup/listForTree",
            queryParams: {{ Js::from($data->search) }},
            showHeader: false,
            rownumbers: false,
            idField: 'id',
            treeField: 'name',
            showFooter:true,
            border: false,
            columns: [[
                {
                    field: 'name',
                    formatter: (value, rowData) => {
                        if (rowData.type === 'relationGroup') {
                            return `<div><div class='color-frame'>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                        }
                        if (rowData.type === 'rgRelationType') {
                            return `<div><div><span class='fe-name color_${rowData.idColor}'>${value[0]}</span></div><div class='definition'>${value[1]}</div></div>`;
                        }
                        if (rowData.type === 'relationType') {
                            return `<div><div class='color-lu'>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                        }
                    }
                },
            ]],
            onClickRow: (row) => {
                if (row.type === 'relationGroup') {
                    let idRelationGroup = row.id.substring(1);
                    window.location.href = `/relationgroup/${idRelationGroup}/edit`;
                }
                if (row.type === 'relationType') {
                    let idRelationType = row.id.substring(1);
                    window.location.href = `/relationtype/${idRelationType}/main`;
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