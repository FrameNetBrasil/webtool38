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
        <script type="text/javascript">
            window.annotation = {
                document: {{ Js::from($document) }},
                documentMM: {{ Js::from($documentMM) }},
                loadObjects: () => {
                    return new Promise((resolve, reject) => {
                        console.log('ajax call',annotation.document.idDocument);
                        $.ajax({
                            url: "/annotation/dynamicMode/gridObjects/" + annotation.document.idDocument,
                            method: "GET",
                            dataType: "json",
                            success: (response) => {
                                resolve(response);
                            }
                        });
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
            $(function () {
                @include("Annotation.DynamicMode.Scripts.data")

            })
        </script>
    </x-slot:script>
</x-layout.dynamic-annotation>
