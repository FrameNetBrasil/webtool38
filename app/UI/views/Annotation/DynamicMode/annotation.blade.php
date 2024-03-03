<x-layout.dynamic-annotation>
    <x-slot:title>
        Annotation: Dynamic Mode
    </x-slot:title>
    <x-slot:actions>
    </x-slot:actions>
    <x-slot:meta>
        Corpus: {{$document->corpus->name}} | Document: {{$document->name}}
    </x-slot:meta>
    <x-slot:video>
        @include("Annotation.DynamicMode.Annotation.video")
    </x-slot:video>
    <x-slot:grid>
        @include("Annotation.DynamicMode.Annotation.grid")
    </x-slot:grid>
    <x-slot:script>
        <script type="text/javascript" src="/scripts/vatic/dist/compatibility.js"></script>
        <script type="text/javascript" src="/scripts/vatic/dist/jszip.js"></script>
        <script type="text/javascript" src="/scripts/vatic/dist/StreamSaver.js"></script>
        <script type="text/javascript" src="/scripts/vatic/dist/polyfill.js"></script>
        <script type="text/javascript" src="/scripts/vatic/dist/jsfeat.js"></script>
        <script type="text/javascript" src="/scripts/vatic/dist/nudged.js"></script>
        <script type="text/javascript" src="/scripts/vatic/dist/pouchdb.min.js"></script>
        <script type="text/javascript" src="/scripts/vatic/vatic.js"></script>
        <script type="text/javascript" src="/scripts/vatic/FramesManager.js"></script>
        <script type="text/javascript" src="/scripts/vatic/OpticalFlow.js"></script>
        <script type="text/javascript" src="/scripts/vatic/BoundingBox.js"></script>
        <script type="text/javascript" src="/scripts/vatic/Frame.js"></script>
        <script type="text/javascript" src="/scripts/vatic/DynamicObject.js"></script>
        <script type="text/javascript" src="/scripts/vatic/ObjectsTracker.js"></script>
        <script type="text/javascript">
            const evtDOObjects = new Event("doObjects:ready");
            window.annotation = {
                document: {{ Js::from($document) }},
                documentMM: {{ Js::from($documentMM) }},
                objects: [],
                loadObjects: async function () {
                    await $.ajax({
                        url: "/annotation/dynamicMode/gridObjects/" + annotation.document.idDocument,
                        method: "GET",
                        dataType: "json",
                        success: (response) => {
                            window.annotation.objects = response;
                            //document.dispatchEvent(evtDOObjects);
                            Alpine.store('doStore').dataState = 'loaded';
                        }
                    });
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
                listSentences: (idDocumentMM) => {
                    let url = "/index.php/webtool/annotation/dynamic/sentences/" + idDocumentMM;
                    let sentences = [];
                    manager.doAjax(url, (response) => {
                        sentences = response;
                    }, {});
                    return sentences;
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
                updateObject: (params) => {
                    return new Promise((resolve, reject) => {
                        try {
                            let result = {};
                            let url = "/index.php/webtool/annotation/dynamic/updateObject";
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

            @include("Annotation.DynamicMode.Annotation.objectManager")
            @include("Annotation.DynamicMode.Annotation.store")


            $(function () {


            })
        </script>
    </x-slot:script>
</x-layout.dynamic-annotation>
