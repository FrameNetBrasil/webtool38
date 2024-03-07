annotation.api = {
    loadObjects: async function () {
        await $.ajax({
            url: "/annotation/dynamicMode/gridObjects/" + annotation.document.idDocument,
            method: "GET",
            dataType: "json",
            success: (response) => {
                annotation.objectList = response;
                //document.dispatchEvent(evtDOObjects);
                Alpine.store('doStore').dataState = 'loaded';
            }
        });
    },
    deleteObject: async (idObjectMM) => {
        let result = null;
        await $.ajax({
            url: "/annotation/dynamicMode/" + idObjectMM,
            method: "DELETE",
            dataType: "json",
            data: {
                _token: annotation._token
            },
            success: (response) => {
                result = response;
            }
        });
        return result;
    },

    deleteObjects: (toDelete) => {
        let params = {
            toDelete: toDelete,
        };
        try {
            let url = "/index.php/webtool/annotation/multimodal/deleteObjects";
            manager.doAjax(url, (response) => {
                if (response.type === 'success') {
                    // $.messager.alert('Ok', 'Objects deleted.','info');
                } else if (response.type === 'error') {
                    throw new Error(response.message);
                }
            }, params);
        } catch (e) {
            $.messager.alert('Error', e.message, 'error');
        }
    },
    loadSentences: async () => {
        let result = null;
        await $.ajax({
            url: "/annotation/dynamicMode/gridSentences/" + annotation.document.idDocument,
            method: "GET",
            dataType: "json",
            success: (response) => {
                result = response;
            }
        });
        return result;
    },
    listFrame: () => {
        let url = "/index.php/webtool/data/frame/combobox";
        let frames = [];
        manager.doAjax(url, (response) => {
            frames = response;
        }, {});
        return frames;
    },
    listFrameElement: () => {
        let url = "/index.php/webtool/data/frameelement/listAllDecorated";
        let frames = [];
        manager.doAjax(url, (response) => {
            frames = response;
        }, {});
        return frames;
    },
    updateObject: async (params) => {
        params._token = annotation._token;
        let result = null;
        await $.ajax({
            url: "/annotation/dynamicMode/updateObject",
            method: "POST",
            dataType: "json",
            data: params,
            success: (response) => {
                result = response;
            }
        });
        return result;
    },
    updateBBox: async (params) => {
        params._token = annotation._token;
        let result = null;
        await $.ajax({
            url: "/annotation/dynamicMode/updateBBox",
            method: "POST",
            dataType: "json",
            data: params,
            success: (response) => {
                result = response;
            }
        });
        return result;
    },

    updateObjectData: (params) => {
        return new Promise((resolve, reject) => {
            try {
                let result = {};
                let url = "/index.php/webtool/annotation/dynamic/updateObjectData";
                manager.doAjax(url, (response) => {
                    if (response.type === 'success') {
                        resolve(response.data);
                    } else if (response.type === 'error') {
                        throw new Error(response.message);
                    }
                }, params);

            } catch (e) {
                $.messager.alert('Error', e.message, 'error');
            }
        });
    }
}
