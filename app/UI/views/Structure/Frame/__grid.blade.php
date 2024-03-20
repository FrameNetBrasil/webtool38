<div id="mainGridTable">
</div>
<script>
    $(function () {
        var table = new Tabulator("#mainGridTable", {
            height: "100%",
            layout: "fitColumns",
            placeholder: "No Data Set",
            columnCalcs:"true",
            pagination:false,
            columns: [
                {
                    field: 'name',
                    // formatter: (value, rowData) => {
                    //     if (rowData.type === 'frame') {
                    //         return `<div><div class='color-frame'>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                    //     }
                    //     if (rowData.type === 'fe') {
                    //         return `<div><div><span class='fe-name color_${rowData.idColor}'>${value[0]}</span></div><div class='definition'>${value[1]}</div></div>`;
                    //     }
                    //     if (rowData.type === 'lu') {
                    //         return `<div><div class='color-lu'>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                    //     }
                    //     if (rowData.type === 'feFrame') {
                    //         return `<div><div><span class='fe-name color_${rowData.idColor}'>${value[0]}</span>&nbsp;<span class='fe-name'>[${value[2]}]</span></div><div class='definition'>${value[1]}</div></div>`;
                    //     }
                    //     if (rowData.type === 'luFrame') {
                    //         return `<div><div class='color-lu'>${value[0]}&nbsp;<span class='fe-name'>[${value[2]}]</span></div><div class='definition'>${value[1]}</div></div>`;
                    //     }
                    // }
                },
            ],
        });
        table.on("tableBuilt", function(){
            table.setData("/frame/listForTree");
        });

    });

</script>
