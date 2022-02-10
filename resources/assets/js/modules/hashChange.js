export default () => {
    window.addEventListener('hashchange', urlChange);

    // Check url
    let target = window.location.hash.substring(1);

    if (target) {
        urlChange();
    }

    function urlChange() {

        // Close menu
        document.querySelector('#megamenu-drawer').classList.remove('active');
        document.querySelector('.js-drawer').classList.remove('clicked');
        document.querySelector('.megamenu-new__burger').classList.remove('clicked');

        // Emit event on window depending on mq
        let mqList = window.matchMedia('(min-width: 768px)');
        let evt;


        if (!mqList.matches) {
            evt = new CustomEvent('AppHashChangeMobile', { detail: { hashChange: true } });
        } else {
            evt = new CustomEvent('AppHashChangeDesktop', { detail: { hashChange: true } });
        }
        window.dispatchEvent(evt);
    }
}