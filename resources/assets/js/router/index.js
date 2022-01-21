import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/browse-networks',
            component: () => import('../pages/browse-networks'),
        },
        {
            path: '/',
            component: () => import('../pages/search'),
            meta: {
                title: 'AllState Benefits'
            }
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

const DEFAULT_TITLE = 'AllState Benefits';
// @TODO (Anton Sydorenko) - write titles for each page
router.afterEach((to, from) => {
    Vue.nextTick(() => {
        document.title = to.meta.title || DEFAULT_TITLE;
    });
});

export default router;
