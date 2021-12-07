// import 'core-js/es/promise';

// // import JS
// import './js/main';

import Vue from 'vue';
import router from './router';

// vue plugins
import './vue-plugins';

// // Import Vue Components
import SearchBlock from './components/SearchBlock';
import App from './App.vue';

Vue.component('search-block', SearchBlock);
// Mount Vue
if (document.getElementById('vue-app')) {
    new Vue({
        router: router,
        ...App
    }).$mount('#vue-app');
}

for (let el of document.getElementsByClassName('vue-app')) {
    new Vue({
        el: el,
        router
    });
}
