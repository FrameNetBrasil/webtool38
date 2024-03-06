<script type="text/javascript">
    // layers/annotation.js
    document.addEventListener('alpine:init', () => {
        Alpine.store('annotation', {
            sentence: '',
            words: [],
            objects: [],
            image: {
                width: {{$imageMM->width}},
                height: {{$imageMM->height}}
            },
            createWords(sentence) {
                this.sentence = sentence.trim();
                let words = this.sentence.split(' ');
                let iword = [];
                iword.push({
                    i: 0,
                    id: 'word_' + 0,
                    word: '',
                    color: 'black',
                    idObject: -1,
                    idObjectSentenceMM: -1,
                });
                for (let i = 1; i <= words.length; i++) {
                    iword.push({
                        i: i,
                        id: 'word_' + i,
                        word: words[i - 1],
                        color: 'black',
                        idObject: -1,
                        idObjectSentenceMM: -1,
                    });
                }
                this.words = iword;
            },
            setObjects(objects) {
                let order = 0;
                for (var idObjectSentenceMM in objects) {
                    let object = objects[idObjectSentenceMM]
                    let phrase = '';
                    object.startWord = parseInt(object.startWord);
                    object.endWord = parseInt(object.endWord);
                    object.idObjectSentenceMM = parseInt(object.idObjectSentenceMM);
                    if (this.words.length > 0) {
                        if (object.startWord >= 0) {
                            for (let i = object.startWord; i <= object.endWord; i++) {
                                phrase = phrase + this.words[i].word + ' ';
                            }
                        }
                    }
                    object.idObject = ++order;
                    object.text = phrase;
                }
                this.objects = objects;
                console.log(this.objects)
            },
            decorateWords() {
                let bboxes;
                for (var idObjectSentenceMM in this.objects) {
                    let object = this.objects[idObjectSentenceMM]
                    for (let w = object.startWord; w <= object.endWord; w++) {
                        bboxes = object.bboxes;
                        if ((bboxes[0] === undefined) && (object.name !== 'scene')) {
                            this.words[w].idObject = 0;
                            this.words[w].idObjectSentenceMM = -1;
                        } else {
                            this.words[w].idObject = object.idObject;
                            this.words[w].idObjectSentenceMM = parseInt(object.idObjectSentenceMM);
                        }
                    }
                }
            },
            newBboxElement(bbox) {
                let box = document.createElement('div');
                box.className = 'wt-anno-box-border-' + bbox.idObject;
                const image = document.getElementById("image");
                image.appendChild(box);
                let x = parseInt(bbox.x);
                let y = parseInt(bbox.y);
                let w = parseInt(bbox.width) - 8;
                let h = parseInt(bbox.height) - 8;
                box.style.width = w + 'px';
                box.style.height = h + 'px';
                box.style.left = x + 'px';
                box.style.top = y + 'px';
                box.style.position = 'absolute';
                box.style.zIndex = 3;
                let id = document.createElement('div');
                id.className = 'objectId';
                id.innerHTML = bbox.tag;
                box.appendChild(id);
            },
            drawBBoxes() {
                let fakeIdBBox = 0;
                let newBBoxes = {};
                let idBBox = '';
                for (var idObjectSentenceMM in this.objects) {
                    let object = this.objects[idObjectSentenceMM]
                    let bboxes = object.bboxes;
                    if (bboxes[0] === undefined) {
                        if (object.name === 'scene') {
                            if (newBBoxes[0] === undefined) {
                                newBBoxes[0] = {
                                    tag: 'scene ' + object.idObject,
                                    idObject: object.idObject,
                                    x: 0,
                                    y: 0,
                                    width: this.image.width,
                                    height: this.image.height
                                }
                            }
                        }
                    } else {
                        for (let bbox of bboxes) {
                            idBBox = 'box_' + bbox.x + '_' + bbox.y + '_' + bbox.width + '_' + bbox.height;
                            if (newBBoxes[idBBox] === undefined) {
                                newBBoxes[idBBox] = {
                                    tag: object.idObject,
                                    idObject: object.idObject,
                                    x: bbox.x,
                                    y: bbox.y,
                                    width: bbox.width,
                                    height: bbox.height
                                }
                            } else {
                                newBBoxes[idBBox].tag = newBBoxes[idBBox].tag + ',' + object.idObject;
                            }
                        }
                    }
                    for (let idBBox in newBBoxes) {
                        this.newBboxElement(newBBoxes[idBBox]);
                    }
                }
            },

        })


        $(function () {
            window.ky.get('/annotation/staticFrameMode1/sentence/{{$idStaticSentenceMM}}/object', {}).json().then((data) => {
                console.log(data);
                window.annotation = {
                    data: data,
                }
                Alpine.store('annotation').createWords(window.annotation.data.sentence.text);
                Alpine.store('annotation').setObjects(window.annotation.data.objects);
                Alpine.store('annotation').decorateWords();
                Alpine.store('annotation').drawBBoxes();
            })
        })
    })
</script>
