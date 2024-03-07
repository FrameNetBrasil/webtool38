annotation.gridSentences = {
    columns: [
        {
            field: 'idAnnotationSet',
            hidden: true,
        },
        {
            field: 'idDynamicSentenceMM',
            hidden: true,
        },
        {
            field: 'startTime',
            title: 'Start Frame [Time]',
            align: 'right',
            width: 112,
        },
        /*
        {
            field: 'endTime',
            title: 'End Frame [Time]',
            align: 'right',
            width: 112,
        },
        {
            field: 'play',
            width: 24,
            title: '',
            formatter: function (value, row, index) {
                return '<i class="fas fa-play"></i>';
            },
        },
        {
            field: 'play3',
            width: 56,
            title: '',
            formatter: function (value, row, index) {
                return '<i class="fas fa-play"></i> +- 3';
            },
        },
        {
            field: 'play5',
            width: 56,
            title: '',
            formatter: function (value, row, index) {
                return '<i class="fas fa-play"></i> +- 5';
            },
        },
        
         */
        {
            field: 'decorated',
            title: 'Sentence',
            align: 'left',
            width: 300,
        },
    ],
    async loadSentences() {
        let sentences = await annotation.api.loadSentences();
        console.log(sentences);
        $('#gridSentences').datagrid('loadData', sentences);
    }
}



$('#gridSentences').datagrid({
    data: [],
    border: 1,
    width: '100%',
    fit: true,
    idField: 'idDynamicSentenceMM',
    showHeader: true,
    singleSelect: true,
    nowrap: false,
    columns: [annotation.gridSentences.columns],
    onClickCell: function (index, field, value) {
        let currentVideoState = Alpine.store('doStore').currentVideoState;
        let newObjectState = Alpine.store('doStore').newObjectState;
        if (currentState === 'videoPaused') {
            console.log(index, field, value);
            /*
            let rows = $('#gridSentences').datagrid('getRows');
            let row = rows[index];
            if (field === 'startTimestamp') {
                let startFrame = that.frameFromTime(value);
                that.$store.commit('currentFrame', startFrame);
            }
            if (field === 'startFrame') {
                that.$store.commit('currentFrame', value);
            }
            if (field === 'endTimestamp') {
                let startFrame = that.frameFromTime(value);
                that.$store.commit('currentFrame', startFrame);
            }
            if (field === 'endFrame') {
                that.$store.commit('currentFrame', value);
            }
            if (field === 'text') {
                let startFrame = that.frameFromTime(row.startTimestamp);
                that.$store.commit('currentFrame', startFrame);
            }
            if (field === 'play') {
                let startFrame = that.frameFromTime(row.startTimestamp);
                that.$store.commit('currentFrame', startFrame);
                let stopFrame = that.frameFromTime(row.endTimestamp);
                that.$store.commit('currentStopFrame', stopFrame);
                that.$store.commit('currentState', 'videoPlaying');
            }
            if (field === 'play3') {
                let startFrame = that.frameFromTime(row.startTimestamp);
                let endFrame = that.frameFromTime(row.endTimestamp);
                let start = startFrame - (annotationVideoModel.fps * 3)
                let stop = endFrame + (annotationVideoModel.fps * 3);
                that.$store.commit('currentFrame', start);
                that.$store.commit('currentStopFrame', stop);
                that.$store.commit('currentState', 'videoPlaying');
            }
            if (field === 'play5') {
                let startFrame = that.frameFromTime(row.startTimestamp);
                let endFrame = that.frameFromTime(row.endTimestamp);
                let start = startFrame - (annotationVideoModel.fps * 5)
                let stop = endFrame + (annotationVideoModel.fps * 5);
                that.$store.commit('currentFrame', start);
                that.$store.commit('currentStopFrame', stop);
                that.$store.commit('currentState', 'videoPlaying');
            }
            */

        }
    },
});
