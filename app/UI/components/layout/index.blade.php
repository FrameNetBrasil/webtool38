<!DOCTYPE html>
<head>
    <meta name="Generator" content="Maestro 3.0; http://maestro.org.br">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>{!! config('webtool.pageTitle') !!}</title>
    <meta name="description" content="Framenet Brasil Webtool 3.8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Filled"
          rel="stylesheet"
          type="text/css">

    <script type="text/javascript" src="/scripts/htmx/htmx.min.js"></script>

    <script type="text/javascript" src="/scripts/jquery-easyui-1.10.17/jquery.min.js"></script>


    <script type="text/javascript" src="/scripts/maestro/manager.js"></script>

    <script type="text/javascript" src="/scripts/pdf/jspdf.debug.js"></script>
    <script type="text/javascript" src="/scripts/pdf/html2canvas.min.js"></script>
    <script type="text/javascript" src="/scripts/pdf/html2pdf.min.js"></script>
    <script defer src="/scripts/alpinejs/cdn.min.js"></script>


    <!--
    <link rel="stylesheet" href="/scripts/jquery-easyui-1.5.2/themes/icon.css">
    <link rel="stylesheet" href="/scripts/jointJS/joint.min.css">
    <link rel="stylesheet" href="/scripts/trumbowyg/ui/trumbowyg.css">
    <link rel="stylesheet" href="/scripts/jsplumb/jsplumbtoolkit-defaults.css">
    <link rel="stylesheet" href="/scripts/jsplumb/jsplumbtoolkit-demo.css">

    <link rel="stylesheet" href="/scripts/jquery-easyui-1.10.17/themes/default/easyui.css">
-->
    <!--
    <script type="text/javascript" src="/scripts/jquery-manager/jquery.manager.core.js"></script>

    <script type="text/javascript"
            src="/scripts/fontawesome-free-5.0.9/svg-with-js/js/fontawesome-all.min.js"></script>
    <script type="text/javascript"
            src="/scripts/fontawesome-free-5.0.9/svg-with-js/js/fa-v4-shims.min.js"></script>
-->

    <script type="text/javascript" src="/scripts/jquery-easyui-1.10.17/jquery.easyui.min.js"></script>

    <script type="text/javascript" src="/scripts/maestro/notify.js"></script>
    <script type="text/javascript" src="/scripts/maestro/webcomponents.js"></script>

    <link rel="stylesheet" type="text/css" href="/scripts/jointjs/dist/joint.css"/>

    <script type="text/javascript" src="/scripts/video-js-8.11.5/video.min.js"></script>
    <link href="/scripts/video-js-8.11.5/video-js.css" rel="stylesheet"/>

    <!--
    <link rel="stylesheet" href="/scripts/helix-ui/helix-ui.css" />
    <link rel="stylesheet" href="/scripts/helix-ui/docs.css"/>
    -->
    <script src="/scripts/helix-ui/webcomponents-loader.js"></script>

    <!--
    <script type="text/javascript" src="/scripts/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript"
            src="/scripts/bootstrapvalidator-dist-0.5.3/dist/js/bootstrapValidator.js"></script>
    <script type="text/javascript"
            src="/scripts/bootstrapvalidator-dist-0.5.3/dist/js/language/pt_BR.js"></script>
    <script type="text/javascript" src="/scripts/bootstrap-switch-master/js/bootstrap-switch.min.js"></script>
    <script type="text/javascript" src="/scripts/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js"></script>
    <script type="text/javascript" src="/scripts/jquery.hotkeys-master/jquery.hotkeys.js"></script>
    <script type="text/javascript" src="/scripts/bootstrap-wysiwyg-master/bootstrap-wysiwyg.js"></script>

    <script type="text/javascript" src="/scripts/jquery.class.js"></script>
    -->
    <!--
    <script type="text/javascript" src="/scripts/cola_v3/cola.v3.js"></script>
    <script type="text/javascript" src="/scripts/d3/d3.graph.js"></script>
    <script type="text/javascript" src="/scripts/d3/d3.tree.js"></script>
    <script type="text/javascript" src="/scripts/d3/d3.graphtree.js"></script>
    <script type="text/javascript" src="/scripts/jointJS/lodash.min.js"></script>
    <script type="text/javascript" src="/scripts/jointJS/backbone-min.js"></script>
    <script type="text/javascript" src="/scripts/jointJS/joint.min.js"></script>
    <script type="text/javascript" src="/scripts/jointJS/joint.shapes.devs.js"></script>
    <script type="text/javascript" src="/scripts/jointJS/joint.shapes.frame.js"></script>
    <script type="text/javascript" src="/scripts/jointJS/joint.shapes.entity.js"></script>
    <script type="text/javascript" src="/scripts/jquery.md5.js"></script>
    <script type="text/javascript" src="/scripts/trumbowyg/trumbowyg.min.js"></script>
    <script type="text/javascript" src="/scripts/jsplumb/jsplumb.js"></script>
    <script type="text/javascript" src="/scripts/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/scripts/d3-7.0.0/d3.min.js"></script>
    <script type="text/javascript" src="/scripts/@hpcc-js/wasm/dist/index.min.js"></script>
    <script type="text/javascript" src="/scripts/d3-graphviz-4.0.0/d3-graphviz.js"></script>
    -->
    <!--
    <script type="text/javascript" src="/scripts/theme/extensions.js"></script>
    <script type="text/javascript" src="/scripts/theme/controls.js"></script>
    -->
    @vite(['resources/js/app.js'])
</head>
<body
    class="hxVertical"
    hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
    id="top"
>
@include('components.head')
@include('components.head-small')

<div id="app">
    <div id="stage">
        <main role="main" id="content" class="component-content">
            {{$slot}}
            <wt-go-top id="myButton" label="Top" offset="64"></wt-go-top>
            <footer id="foot">
                &copy; 2014-2024 FrameNet Brasil Lab, UFJF.
            </footer>
        </main>
    </div>
</div>



<!-- App Scripts Go Here -->
<script src="/scripts/lodash/lodash.js"></script>
<script src="/scripts/backbone/backbone.js"></script>
<script src="/scripts/jointjs/dist/joint.js"></script>
<script src="/scripts/dagre/dist/dagre.js"></script>
<script src="/scripts/utils/md5.min.js"></script>
<!--
<script src="/scripts/tabulator-6.0/js/tabulator.min.js"></script>
-->

<script nomodule src="/scripts/helix-ui/helix-ui.js"></script>
<script nomodule>
    HelixUI.initialize();
</script>

<script type="module">
    import HelixUI from '/scripts/helix-ui/helix-ui.module.js';

    HelixUI.initialize();
</script>

<style>
    .wt-go-top {
        display: none; /* Hidden by default */
        position: fixed; /* Fixed/sticky position */
        bottom: 20px; /* Place the button at the bottom of the page */
        right: 30px; /* Place the button 30px from the right */
        z-index: 99; /* Make sure it does not overlap */
        border: none; /* Remove borders */
        outline: none; /* Remove outline */
        /*background-color: red; !* Set a background color *!*/
        /*color: white; !* Text color *!*/
        /*cursor: pointer; !* Add a mouse pointer on hover *!*/
        /*padding: 15px; !* Some padding *!*/
        /*border-radius: 10px; !* Rounded corners *!*/
        /*font-size: 18px; !* Increase font size *!*/
    }

    .wt-go-top:hover {
        /*background-color: #555; !* Add a dark-grey background on hover *!*/
    }
</style>

<!--

<header class="flex flex-column">
    <section class="headerPane grid grid-nogutter">
        <section class="title md:col-6 sm:col-12">
            {!! config("webtool.mainTitle") !!}
</section>
<nav class="md:col-6 sm:col-12 text-right">
@include('components.userdata')
</nav>
</section>

<nav class="menuPane">
@include('components.menu')
</nav>
</header>

<main class="centerPane">
<section id="contentPane">
    <div data-options="region:'center',border:false">
{{ $slot }}
</div>
</section>
</main>
<script>
document.body.addEventListener("notify", function (evt) {
manager.messager(evt.detail.type, evt.detail.message);
})
$(function () {

$('#contentPane').layout({
    fit: true
})
@stack('onload')
})
</script>
-->
</body>
</html>
