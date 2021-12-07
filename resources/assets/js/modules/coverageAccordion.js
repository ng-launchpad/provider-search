export default () => {
    let accordion = document.querySelectorAll('.js-coverage-accordion');

    if (accordion) {
        setupAccordion();
    }

    function setupAccordion() {
        window.addEventListener('AppHashChangeMobile', urlChange);

        accordion.forEach((item) => {
            let btn = item.querySelector('.js-accordion-button');
            let content = item.querySelector('.js-accordion-content');

            btn.addEventListener('click', (evt) => {
                evt.preventDefault();

                // Manually add hash
                let hash = evt.target.href.split('#')[1];

                if (window.location.hash.substring(1) === hash) {
                    history.pushState("", document.title, window.location.href.split('#')[0]);
                    urlChange();
                } else {
                    window.location.hash = hash;
                }
            });
        });
    }

    function urlChange() {

        // Clear open states and active states
        let activeButton = document.querySelector('.js-accordion-button.is-active');
        if (activeButton) {
            activeButton.classList.remove('is-active')
        };

        let activeContent = document.querySelector('.js-accordion-content.is-active');
        if (activeContent) {
            activeContent.classList.remove('is-active');
        }

        if (window.location.hash) {
            let btn = document.querySelector(`.js-accordion-button[href="${window.location.hash}"]`);
            let content = btn.parentElement.querySelector('.js-accordion-content');
            btn.classList.add('is-active');
            content.classList.add('is-active');

            // Scroll to appropriate area
            setTimeout(() => {
                window.scroll({
                    top: document.querySelector('.js-accordion-button.is-active').offsetTop - 80,
                    left: 0,
                    behavior: 'smooth'
                });
            }, 280);
        }


    }
}
