import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);

const routes = new Router({
    mode: 'history',
    routes: [
        {
            path: '/browse-networks',
            component: () => import('../pages/browse-networks'),
        },
        {
            path: '/',
            component: () => import('../pages/search')
        },
        {
            path: '/results',
            component: () => import('../pages/results'),
            props: route => ({ query: route.query })
        },
        {
            path: '/allstate',
            component: () => import('../pages/browsing'),
        },
        {
            path: '/provider/:id',
            component: () => import('../pages/provider'),
        },
        {
            path: '/facility/:id',
            component: () => import('../pages/facility'),
        }
    ],
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
});

export default routes;
