import { createRouter, createWebHistory } from "vue-router";
import dashboard from "./router";
import Store from "../store";

const routes = [...dashboard];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    let documentTitle = `${to.meta.name}`;
    if (to.query.title) {
        documentTitle += ` ${to.query.title}`;
    }
    document.title = documentTitle;

    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (to.meta.requiresAuth && !Store.getters.GET_AUTH_STATUS) {
            next('/login');
        } else {
            next(); // Proceed to the route
        }
    } else if (to.matched.some(record => record.meta.guest)) {
        if (Store.getters.GET_AUTH_STATUS) {
            next({
                name: 'hotel-home'
            });
        } else {
            next(); // Proceed to the route
        }
    } else {
        // Route does not require authentication
        next();
    }

    
});

export default router;
