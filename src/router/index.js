import { createRouter, createWebHistory } from "vue-router";
import Login from "@/components/Login.vue";
import Fix from "@/components/Fix.vue";
import CreateAccount from "@/components/CreateAccount.vue";
import ForgotPassword from "@/components/ForgotPassword.vue";
import ResetPassword from "@/components/ResetPassword.vue";
import CustomerProfile from "@/components/CustomerProfile.vue";
import AdminDashboard from "@/components/AdminDashboard.vue";

const routes = [
    { path: "/", redirect: "/login" },
    { path: "/login", component: Login , meta: { guestOnly: true }},
    { path: "/fix", component: Fix , meta: { guestOnly: true }},
    { path: "/register", component: CreateAccount, meta: { guestOnly: true } },
    { path: "/forgot-password", component: ForgotPassword , meta: { guestOnly: true }},
    { path: "/reset-password", component: ResetPassword , meta: { guestOnly: true }},
    { path: "/admin-dashboard", component: AdminDashboard, meta: { requiresAuth: true } },
    { path: "/customer-dashboard", component: CustomerProfile, meta: { requiresAuth: true } },
    { path: "/forbidden", component: () => import("../components/Forbidden.vue") },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("jwt");
    let isValidToken = false;

    if (token) {
        try {
            const payload = JSON.parse(atob(token.split('.')[1]));
            isValidToken = payload.exp * 1000 > Date.now();
        } catch (e) {
            console.error("Invalid JWT:", e);
            localStorage.removeItem("jwt");
        }
    }

    if (to.meta.requiresAuth && !token) {
        alert("Please log in to access this page.");
        return next("/login");
    }
    const noRedirectRoutes = ["/register", "/login", "/forgot-password", "/reset-password"];


    if (to.meta.guestOnly && token && !noRedirectRoutes.includes(to.path)) {
        return next("/customer-dashboard");
    }

    next();
});

export default router;