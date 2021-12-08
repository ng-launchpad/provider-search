import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);

import BrowseNetworksPage from '../pages/browse-networks';
import SearchPage from '../pages/search';
import ResultsPage from '../pages/results';
import ProviderPage from '../pages/provider';
import FacilityPage from '../pages/facility';
import BrowsingPage from '../pages/browsing';

const routes = new Router({
    mode: 'history',
    routes: [
        {
            path: '/browse-networks',
            component: BrowseNetworksPage,
        },
        {
            path: '/',
            component: SearchPage
        },
        {
            path: '/results',
            component: ResultsPage,
            props: route => ({ query: route.query })
        },
        {
            path: '/st-vincents',
            component: BrowsingPage,
        },
        {
            path: '/provider/:id',
            component: ProviderPage,
        },
        {
            path: '/facility/:id',
            component: FacilityPage,
        }
    ],
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
});

export default routes;
