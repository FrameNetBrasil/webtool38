<x-layout.index>
    <div class="wt-container-center">
        <div class="wt-container-center-content">
            <img src="/images/fnbr_logo.png"/>
            <a class="btn-login">Sign In</a>
        </div>
    </div>
</x-layout.index>

<script>
    $(document).ready(function () {
        $('.btn-login').click(function (e) {
            e.preventDefault();
            window.location = "/auth0Login";
        });
        $('.btn-logout').click(function (e) {
            e.preventDefault();
            window.location = "/auth0Logout";
        });
    });
</script>

<style>
    .btn-login {
        display:block;
        font-size: 140%;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 0;
        background-color: #b30000;
        color: white;
        text-align: center;
        padding: 4px;
        cursor: pointer;
    }

    .btn-login:hover {
        background-color: #ff6666;
    }

    .btn-login:focus {
        outline: none !important;
    }

    .btn-login:disabled {
        background-color: #333;
        color: #666;
    }
</style>


