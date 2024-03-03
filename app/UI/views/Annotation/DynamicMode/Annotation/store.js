document.addEventListener('alpine:init', () => {
    window.doStore = Alpine.store('doStore', {
        dataState: '',
        timeCount: 0,
        frameCount: 1,
        timeDuration: 0,
        frameDuration: 0,
        currentVideoState: 'paused',
        currentFrame: 1,
        updateObjectList() {
            this.dataState = 'loading';
            window.annotation.loadObjects();
            console.log(window.annotation.objects);
        },
        init() {
            window.objectManager.init();
            this.updateObjectList();
        },
        updateCurrentFrame(frameNumber) {
            this.frameCount = this.currentFrame = frameNumber;
        }
    })

    Alpine.effect(() => {
        const timeCount = Alpine.store('doStore').timeCount;
        //console.log('timecount change', timeCount);
    })
    Alpine.effect(() => {
        const frameCount = Alpine.store('doStore').frameCount;
        //console.log('framecount change', frameCount);
    })
    Alpine.effect(() => {
        const dataState = Alpine.store('doStore').dataState;
        if (dataState === 'loading') {
            if (!$('#gridObjects').length)  {
                $('#gridObjects').datagrid('loading');
            }
        }
        if (dataState === 'loaded') {
            console.log('Data Loaded');
            let objects = window.annotation.objects;
            window.objectManager.annotateObjects(objects);
            $('#gridObjects').datagrid({
                data: objects
            });
            $('#gridObjects').datagrid('loaded');

        }
    })
})
