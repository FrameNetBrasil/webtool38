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
        <x-select id="rankdir" style="width:60px">
            <option value="TB" selected>TB</option>
            <option value="BT">BT</option>
            <option value="RL">RL</option>
            <option value="LR">LR</option>
        </x-select>
        <label for="align">Align:</label>
        <x-select id="align" style="width:60px">
            <option value="DL" selected>DL</option>
            <option value="DR">DR</option>
            <option value="UL">UL</option>
            <option value="UR">UR</option>
        </x-select>
        <label for="ranksep">RankSep:</label>
        <input id="ranksep" type="range" min="1" max="100" value="50"/>
        <label for="edgesep">EdgeSep:</label>
        <input id="edgesep" type="range" min="1" max="100" value="50"/>
        <label for="nodesep">NodeSep:</label>
        <input id="nodesep" type="range" min="1" max="100" value="50"/>
        <label for="connector">Connector:</label>
        <x-select id="connector" style="width:120px">
            <option value="smooth" selected>smooth</option>
            <option value="curve">curve</option>
            <option value="normal">normal</option>
            <option value="jumpover">jumpover</option>
        </x-select>
    </div>
</div>
