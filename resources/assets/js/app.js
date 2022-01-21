// import 'core-js/es/promise';

// // import JS
// import './js/main';

import Vue from 'vue';
import router from './router';

// vue plugins
import './vue-plugins';

// // Import Vue Components
import App from './App.vue';

const HeaderBackBtn = () => import('./components/HeaderBackBtn');

Vue.component('header-back-btn', HeaderBackBtn);

// Mount Vue
if (document.getElementById('vue-app')) {
    new Vue({
        router: router,
        ...App
    }).$mount('#vue-app');
}

// Mount Vue separate components
for (let el of document.getElementsByClassName('vue-app')){
    new Vue({
        router: router,
        el: el
    });
}
