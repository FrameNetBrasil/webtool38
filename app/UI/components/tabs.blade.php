<div class="wt-tabs" id="{{$id}}">
<div class="wt-tabs-tabs flex flex-row">
    @foreach ($tabs as $idTab => $tabName)
        <div class="wt-tabs-tabs-title {{ $idTab == $active ? 'activeTab' : '' }}" data-tab-value="#{{$idTab}}">{{$tabName}}</div>
    @endforeach
</div>
@foreach ($slots as $idSlot => $slot)
    <div class="wt-tabs-content {{$idSlot == $active ? 'active' : ''}}" id="{{$idSlot}}" data-tab-info>
        {{ $$slot }}
    </div>
@endforeach
</div>

<script type="text/javascript">
    // function to get each tab details
    const tabs = document.querySelectorAll('[data-tab-value]')
    const tabInfos = document.querySelectorAll('[data-tab-info]')
    tabs.forEach(tab => {
        tab.addEventListener('click', (x) => {
            const target = document.querySelector(tab.dataset.tabValue);
            console.log(target,x.target);
            tabInfos.forEach(tabInfo => {
                tabInfo.classList.remove('active')
            })
            tabs.forEach(tab => {
                tab.classList.remove('activeTab')
            })
            target.classList.add('active');
            x.target.classList.add('activeTab');
        })
    })
</script>
