annotation.gridSentences = {
    columns: [
        {
            field: 'idDynamicSentenceMM',
            hidden: true,
        },
        {
            field: 'startTime',
            title: 'Start Frame [Time]',
            align: 'right',
            width: '120px',
            resizable: false,
            formatter: function (value, row, index) {
                return "<span  class='gridPaneFrame'>" + row.startFrame + " [" + row.startTime + "s]" + "</span>";
            },
        },
        {
            field: 'endTime',
            title: 'End Frame [Time]',
            align: 'right',
            width: '120px',
            resizable: false,
            formatter: function (value, row, index) {
                return "<span  class='gridPaneFrame'>" + row.endFrame + " [" + row.endTime + "s]" + "</span>";
            },
        },
        {
            field: 'play',
            width: '28px',
            title: '',
            formatter: function (value, row, index) {
                return `<div class="wt-datagrid-action" style="width:24px; cursor:pointer"><span class='material-icons wt-datagrid-icon wt-icon-play'></span></div>`;
            },
        },
        {
            field: 'play3',
            width: '64px',
            title: '',
            formatter: function (value, row, index) {
                return `<div class="wt-datagrid-action" style="width:64px; cursor:pointer"><span class='material-icons wt-datagrid-icon wt-icon-range'></span> 3s <span class='material-icons wt-datagrid-icon wt-icon-play'></span></div>`;
            },
        },
        {
            field: 'play5',
            width: '64px',
            title: '',
            formatter: function (value, row, index) {
                return `<div class="wt-datagrid-action" style="width:64px; cursor:pointer"><span class='material-icons wt-datagrid-icon wt-icon-range'></span> 5s <span class='material-icons wt-datagrid-icon wt-icon-play'></span></div>`;
            },
        },
        {
            field: 'decorated',
            title: 'Sentence',
            align: 'left',
            width: '900px',
        },
    ],
    dataLoaded: false,
    async onSelectGrid(tab) {
        console.log('select grid', tab);
        if (tab === 'tabSentences') {
            if (!annotation.gridSentences.dataLoaded) {
                console.log('loading');
                await annotation.gridSentences.loadSentences();
                annotation.gridSentences.dataLoaded = true;
            }
        }
    },
    async loadSentences() {
        let sentences = await annotation.api.loadSentences();
        sentences.forEach(o => {
            o.startFrame = annotation.video.frameFromTime(o.startTime);
            o.endFrame = annotation.video.frameFromTime(o.endTime);
        })
        $('#gridSentences').datagrid({data:sentences});
        $('#gridSentences').datagrid('loaded');
    }
}


$('#gridSentences').datagrid({
    data: [],
    // url: "/annotation/dynamicMode/gridSentences/" + annotation.document.idDocument,
    // method: "GET",
    border: 1,
    //width: '100%',
    fit: true,
    idField: 'idDynamicSentenceMM',
    showHeader: true,
    singleSelect: true,
    nowrap: false,
    loadMsg: '',
    columns: [annotation.gridSentences.columns],
    onClickCell: function (index, field, value) {
        let currentVideoState = Alpine.store('doStore').currentVideoState;
        let newObjectState = Alpine.store('doStore').newObjectState;
        if ((currentVideoState === 'paused') && (newObjectState !== 'tracking')) {
            console.log(index, field, value);
            let rows = $('#gridSentences').datagrid('getRows');
            let row = rows[index];
            let startTime = row.startTime;
            let endTime = row.endTime;
            if (field === 'play') {
                console.log('startTime',startTime);
                let playRange = {
                    startFrame: annotation.video.frameFromTime(startTime),
                    endFrame: annotation.video.frameFromTime(endTime)
                }
                annotation.video.playRange(playRange);
            }
            if (field === 'play3') {
                console.log('startTime',startTime - 3);
                let playRange = {
                    startFrame: annotation.video.frameFromTime(startTime - 3),
                    endFrame: annotation.video.frameFromTime(endTime + 3)
                }
                annotation.video.playRange(playRange);
            }
            if (field === 'play5') {
                console.log('startTime',startTime - 5);
                let playRange = {
                    startFrame: annotation.video.frameFromTime(startTime - 5),
                    endFrame: annotation.video.frameFromTime(endTime + 5)
                }
                annotation.video.playRange(playRange);
            }
        }

    },
});
$('#gridSentences').datagrid('loading');
