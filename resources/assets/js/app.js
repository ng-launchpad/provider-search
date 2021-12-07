// import 'core-js/es/promise';

// // import JS
// import './js/main';

import Vue from 'vue';
import router from './router';

// vue plugins
import './vue-plugins';

// // Import Vue Components
import App from './App.vue';

// Mount Vue
if (document.getElementById('vue-app')) {
    new Vue({
        router: router,
        ...App
    }).$mount('#vue-app');
}
