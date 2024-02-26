<table id="mainGridTable">
</table>
<script>
    $(function () {
        $('#mainGridTable').treegrid({
            fit: true,
            url: "/relationgroup/listForTree",
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
                        if (rowData.type === 'relationGroup') {
                            return `<div><div>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                        }
                        if (rowData.type === 'rgRelationType') {
                            return `<div><div><span>${value[0]}</span></div><div class='definition'>${value[1]}</div></div>`;
                        }
                        if (rowData.type === 'relationType') {
                            return `<div><div>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                        }
                    }
                },
            ]],
            onClickRow: (row) => {
                if (row.type === 'relationGroup') {
                    let idRelationGroup = row.id.substring(1);
                    window.location.href = `/relationgroup/${idRelationGroup}/main`;
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
