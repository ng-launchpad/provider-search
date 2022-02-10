<script>
    window.state = {!! $state !!};
    window.networks = {!! $networks !!}
</script>
<script>
    window.onUsersnapCXLoad = function(api) {
        api.init();
    }
    var script = document.createElement('script');
    script.defer = 1;
    script.src = 'https://widget.usersnap.com/global/load/3dfd05f8-d465-496d-801b-ebe880c11799?onload=onUsersnapCXLoad';
    document.getElementsByTagName('head')[0].appendChild(script);
</script>
<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
