<div class="tabs">
    @foreach ($tabs as $tab)
        <span data-tab-value="#{{$tab}}">{{$tab}}</span>
    @endforeach
</div>
@foreach ($slots as $key => $slot)
    <div class="tabs__tab" id="{{$tabs[$key]}}" data-tab-info>
        {{ $$slot }}
    </div>
@endforeach

<script type="text/javascript">
    // function to get each tab details
    const tabs = document.querySelectorAll('[data-tab-value]')
    const tabInfos = document.querySelectorAll('[data-tab-info]')
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = document
                .querySelector(tab.dataset.tabValue);
            tabInfos.forEach(tabInfo => {
                tabInfo.classList.remove('active')
            })
            target.classList.add('active');
        })
    })
</script>

<style>
    * {
        margin: 0;
        padding: 0;
    }

    body {
        background: white;
    }

    .container {
        border: 1px solid grey;
        margin: 1rem;
    }

    [data-tab-info] {
        display: none;
    }

    .active[data-tab-info] {
        display: block;
    }

    .tab-content {
        margin-top: 1rem;
        padding-left: 1rem;
        font-size: 20px;
        font-family: sans-serif;
        font-weight: bold;
        color: rgb(0, 0, 0);
    }

    .tabs {
        border-bottom: 1px solid grey;
        background-color: rgb(16, 153, 9);
        font-size: 25px;
        color: rgb(0, 0, 0);
        display: flex;
        margin: 0;
    }

    .tabs span {
        background: rgb(16, 153, 9);
        padding: 10px;
        border: 1px solid rgb(255, 255, 255);
    }

    .tabs span:hover {
        background: rgb(55, 219, 46);
        cursor: pointer;
        color: black;
    }
</style>
