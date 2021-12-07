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
            path: '/provider-search/browse-networks',
            component: BrowseNetworksPage,
        },
        {
            path: '/',
            component: SearchPage
        },
        {
            path: '/provider-search/results',
            component: ResultsPage,
            props: route => ({ query: route.query })
        },
        {
            path: '/provider-search/st-vincents',
            component: BrowsingPage,
        },
        {
            path: '/provider-search/provider/:id',
            component: ProviderPage,
        },
        {
            path: '/provider-search/facility/:id',
            component: FacilityPage,
        }
    ],
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
});

export default routes;
