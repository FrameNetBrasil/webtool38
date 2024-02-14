<div id="graphPane"
     class="flex flex-column w-full h-full p-0 wt-layout-grapher">
    <div class="flex-none">
        <div class="title">
            {{$title}}
        </div>
    </div>
    <div class="flex-none">
        <div class="options">
            {{$menu}}
        </div>
    </div>

    <div class="flex-grow-1">
        <div class="pane">
            <div id="grapherPane" class="graphPane">
                <div id="paper"></div>
                <div id="layout-controls-container">
                    <div id="layout-controls" class="controls">
                        <label for="ranker">Ranker:</label>
                        <x-select id="ranker" style="width:150px">
                            <option value="network-simplex" selected>network-simplex</option>
                            <option value="tight-tree">tight-tree</option>
                            <option value="longest-path">longer-path</option>
                        </x-select>
                        <label for="rankdir">RankDir:</label>
                        <x-select id="rankdir" style="width:120px">
                            <option value="TB" selected>Top-Bottom</option>
                            <option value="BT">Bottom-Top</option>
                            <option value="RL">Right-Left</option>
                            <option value="LR">Left-Right</option>
                        </x-select>
                        <label for="align">Align:</label>
                        <x-select id="align" style="width:120px">
                            <option value="DL" selected>Down-Left</option>
                            <option value="DR">Down-Right</option>
                            <option value="UL">Up-Left</option>
                            <option value="UR">Up-Right</option>
                        </x-select>
                        <label for="ranksep">RankSep:</label>
                        <input id="ranksep" type="range" min="1" max="100" value="50"/>
                        <label for="edgesep">EdgeSep:</label>
                        <input id="edgesep" type="range" min="1" max="100" value="50"/>
                        <label for="nodesep">NodeSep:</label>
                        <input id="nodesep" type="range" min="1" max="100" value="50"/>
                        <label for="vertices">Vertices:</label>
                        <input type=checkbox checked id="vertices">
                        <label for="connector">Connector:</label>
                        <x-select id="connector" style="width:120px">
                            <option value="smooth" selected>smooth</option>
                            <option value="curve">curve</option>
                            <option value="normal">normal</option>
                            <option value="jumpover">jumpover</option>
                        </x-select>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $(function () {
        window.Grapher = joint.mvc.View.extend({
            options: {
                nodes: [],
                links: [],
                cells: [],
                padding: 50,
                el: document.getElementById('layout-controls'),
                paper: null,
                panAndZoom: null,
                buildGraph: function () {
                    return [];
                },
                cellDblClick: function (cellView) {
                },
                linkEnter: function (linkView) {
                },
                elementEnter: function (elementView) {
                },
            },
            init: function () {
                let that = this;
                let options = this.options;

                this.paper = new joint.dia.Paper({
                    el: document.getElementById('paper'),
                    width: 'calc(100% - 16px)',
                    height: 'calc(100% - 16px)',
                    sorting: joint.dia.Paper.sorting.APPROX
                })

                this.paper.on('blank:pointerdown', (evt, x, y) => {
                    this.panAndZoom.enablePan();
                })

                this.paper.on('cell:pointerup blank:pointerup', (cellView, event) => {
                    this.panAndZoom.disablePan();
                })

                this.paper.on('cell:pointerdblclick', options.cellDblClick)

                this.paper.on('link:mouseenter', options.linkEnter)

                this.paper.on('link:mouseleave', function (linkView) {
                    linkView.removeTools();
                });

                this.paper.on('element:mouseenter', options.elementEnter)

                this.paper.on('element:mouseleave', function (elementView) {
                    elementView.removeTools();
                });

                $('#rankdir').combobox({onChange: () => that.onChange()});
                $('#ranker').combobox({onChange: () => that.onChange()});
                $('#align').combobox({onChange: () => that.onChange()});
                $('#vertices').on('change', () => this.onChange())
                $('#ranksep').on('change', () => this.onChange())
                $('#nodesep').on('change', () => this.onChange())
                $('#edgesep').on('change', () => this.onChange())
                $('#connector').combobox({
                    onChange: () => {
                        let links = this.paper.model.getLinks();
                        let connector = $('#connector').combobox('getValue');
                        for (var link of links) {
                            link.connector(connector);
                            this.paper.findViewByModel(link.id).render();
                        }
                    }
                });
                this.cells = options.buildGraph();
            },
            onChange: function () {
                this.layout();
                this.trigger('layout');
            },
            layout: function () {
                let paper = this.paper;
                let graph = paper.model;
                let cells = this.cells;
                paper.freeze();
                var layoutOptions = this.getLayoutOptions();
                joint.layout.DirectedGraph.layout(cells, layoutOptions);
                if (graph.getCells().length === 0) {
                    // The graph could be empty at the beginning to avoid cells rendering
                    // and their subsequent update when elements are translated
                    graph.resetCells(cells);
                }
                // let x = paper.fitToContent({
                //     padding: 0,
                //     allowNewOrigin: 'any',
                //     useModelGeometry: true
                // });
                // console.log(x);
                paper.unfreeze();
                let h1 = $('#paper').innerHeight();
                let w1 = $('#paper').innerWidth();
                let b = paper.getContentBBox();
                let h2 = b.height;
                let w2 = b.width;
                if (w2 > w1) {
                    paper.scale((w1 / w2))
                } else if (h2 > h1) {
                    paper.scale((h1 / h2))
                }

                this.panAndZoom = window.svgPanZoom(this.paper.svg, {
                    zoomEnabled: true,
                    controlIconsEnabled: true,
                    dblClickZoomEnabled: false,
                    fit: false,
                    center: false,
                });

                this.panAndZoom.enableControlIcons()
                this.panAndZoom.disablePan();
            },
            getLayoutOptions: function () {
                return {
                    dagre: dagre,
                    graphlib: dagre.graphlib,
                    setVertices: $('#vertices').is(":checked") ? true : function (link) {
                        link.set('vertices', []);
                    },
                    setLinkVertices: $('#vertices').is(":checked"),
                    setLabels: true,
                    ranker: $('#ranker').combobox('getValue'),
                    rankDir: $('#rankdir').combobox('getValue'),
                    align: $('#align').combobox('getValue'),
                    rankSep: parseInt($('#ranksep').val(), 10),
                    edgeSep: parseInt($('#edgesep').val(), 10),
                    nodeSep: parseInt($('#nodesep').val(), 10),
                };
            }
        })



    })


</script>

