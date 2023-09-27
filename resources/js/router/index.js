import { createRouter, createWebHistory } from "vue-router";
import dashboard from "./router";

const routes = [...dashboard];

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    // console.log(to);
    let documentTitle = `${to.meta.name}`
    if (to.query.title) {
        documentTitle += ` ${to.query.title}`
    }
    document.title = documentTitle;
    next();
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        const token = localStorage.getItem('token');
        if (!token) {
            // Chưa xác thực, chuyển đến trang đăng nhập hoặc trang khác
            next('/login');
        } else {
            // Đã xác thực, cho phép truy cập tuyến
            next();
        }
    } else {
        // Tuyến không yêu cầu xác thực
        next();
    }
})

export default router;