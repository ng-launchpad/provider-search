import coverageAccordion from './modules/coverageAccordion';
import hashChange from './modules/hashChange';

document.addEventListener("DOMContentLoaded", function(event) {
    coverageAccordion();
    hashChange();

    const ARROW = document.querySelector('#coverage-slider-arrow');

    // Slider
    let slider = document.querySelector('.js-content-slider');
    if (slider) {
        setupSlider(slider);
    }

    function setupSlider(slider) {
        window.addEventListener('AppHashChangeDesktop', urlChange);

        // Add button events
        let buttons = slider.querySelectorAll('.js-slider-button');

        // Check url
        let target = window.location.hash.substring(1);

        buttons.forEach((button, index) => {
            if (button.href) {
                button.addEventListener('click', sliderClicked);

                if (index === 0) {
                    setArrowLeft(button);
                    ARROW.classList.add('visible');
                }

                if (target && button.href.split('#').pop() === target) {
                    setArrowLeft(button);
                    setContentAreas(button.dataset['clickTarget']);
                }
            }
        });
    }

    function urlChange() {
        // Scroll to appropriate area
        window.scroll({
            top: document.querySelector('.coverage-slider__buttons').offsetTop - 100,
            left: 0,
            behavior: 'smooth'
        });

        let button = document.querySelector(`a[href="${window.location.hash}"]`);
        setArrowLeft(button);
        setContentAreas(button.dataset['clickTarget']);
    }

    function sliderClicked(evt) {
        let button = evt.target;

        setArrowLeft(button);
        setContentAreas(evt.target.dataset['clickTarget']);
    }

    function setArrowLeft(button) {
        let midpoint = button.getBoundingClientRect().left + (button.clientWidth / 2);
        ARROW.style.left = `${midpoint}px`;
    }

    function setContentAreas(dataTarget) {
        let contentAreas = document.querySelectorAll('[data-click-target-id]');
        contentAreas.forEach(area => {
            if (area.dataset['clickTargetId'] === dataTarget) {
                area.classList.add('active');
            } else {
                area.classList.remove('active');
            }
        });

        let disclaimerAreas = document.querySelectorAll('[data-click-disclaimer-id]');
        disclaimerAreas.forEach(area => {
            if (area.dataset['clickDisclaimerId'] === dataTarget) {
                area.classList.add('active');
            } else {
                area.classList.remove('active');
            }
        });
    }

    // Tabs
    let tabs = document.querySelector('.js-tabs');
    if (tabs) {
        setupTabs(tabs);
    }

    function setupTabs(tabs) {
        let tabItems = tabs.getElementsByClassName('button');
        // store tabs variable
        function myTabClicks(tabClickEvent){
            tabClickEvent.preventDefault();
            tabClickEvent.stopPropagation();

            for (let i = 0; i < tabItems.length; i++) {
                tabItems[i].classList.remove('button--active');
            }

            let clickedTab = tabClickEvent.currentTarget;
            clickedTab.classList.add('button--active');
            let myContentPanes = document.querySelectorAll('.tabs__pane');

            for (let i = 0; i < myContentPanes.length; i++) {
                myContentPanes[i].classList.remove('tabs__pane--active');
            }

            let anchorReference = tabClickEvent.target;
            let activePaneId = anchorReference.getAttribute('href');
            let activePane = document.querySelector(activePaneId);

            window.requestAnimationFrame(fadeIn(activePane));

        }

        for (let i = 0; i < tabItems.length; i++) {
            tabItems[i].addEventListener('click', myTabClicks);
        }
    }

    window.addEventListener('hashchange', getActiveLink);
    getActiveLink();

    // Sniff active link
    function getActiveLink() {
        const URL = window.location.href;
        const PATH = `/${URL.split('/').pop()}`;
        const SHOP_INSURANCE = document.querySelector('#shop-insurance');

        const MEGAMENU = document.querySelector('#megamenu');
        const MEGAMENU_MAIN = document.querySelector('#megamenu-main');
        const MEGAMENU_DRAWER = MEGAMENU.querySelector('#megamenu-insurance');

        // Tricky stuff to select active nav item
        const EXISTING_CUSTOMERS_PAGES = ['/provider-search/', '/provider-search', '/claims.php', '/claims-help.php'];

        let specItem = MEGAMENU.querySelector('#existing-customers-nav');
        let specItemMobile = MEGAMENU.querySelector('#existing-customers-nav-draw');

        if (specItem && EXISTING_CUSTOMERS_PAGES.some(link => URL.search(new RegExp(link, 'i')) > 0)) {
            specItem.classList.add('active-link');
            if (specItemMobile) {
                specItemMobile.classList.add('active-link');
            }
            return;
        }

        // Check if active link is in shop insurance
        let found = MEGAMENU_DRAWER.querySelector(`a[href="${PATH}"]`);
        if (found) {
            SHOP_INSURANCE.classList.add('active-link');
            return;
        }

        // else in main
        found = MEGAMENU_MAIN.querySelector(`a[href="${PATH}"]`);
        if (found) {
            found.classList.add('active-link');
            return;
        }
    }

    function fadeIn(el) {
        let op = 0.1;
        el.style.opacity = 0;
        el.classList.add('tabs__pane--active');
        let timer = setInterval(() => {
            if (op >= 1){
                clearInterval(timer);
            }
            el.style.opacity = op;
            el.style.filter = 'alpha(opacity=' + op * 100 + ")";
            op += op * 0.1;
        }, 10);
    }
});
