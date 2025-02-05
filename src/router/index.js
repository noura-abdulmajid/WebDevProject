import { createRouter, createWebHistory } from "vue-router";
import Login from "@/components/Login.vue";
import Fix from "@/components/Fix.vue";
import CreateAccount from "../components/CreateAccount.vue";
import ForgotPassword from "../components/ForgotPassword.vue";
import ResetPassword from "../components/ResetPassword.vue";
import CustomerProfile from "../components/CustomerProfile.vue";
import AdminDashboard from "../components/AdminDashboard.vue";


const routes = [
    { path: "/", redirect: "/login" },
    { path: "/login", component: Login },
    { path: "/fix", component: Fix },
    { path: "/register", component: CreateAccount, meta: { guestOnly: true } },
    { path: "/forgot-password", component: ForgotPassword },
    { path: "/reset-password", component: ResetPassword },
    { path: "/admin-dashboard", component: AdminDashboard, meta: { requiresAuth: true, role: "admin" } },
    { path: "/customer-dashboard", component: CustomerProfile, meta: { requiresAuth: true, role: "customer" } },
    { path: "/forbidden", component: () => import("../components/Forbidden.vue") },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token");
    const role = localStorage.getItem("role");

    if (to.meta.requiresAuth) {
        if (!token) {
            alert("Please log in to access this page.");
            return next("/login");
        }

        if (to.meta.requiresAuth) {
            if (!token) return next("/login");
            if (to.meta.role && to.meta.role !== role) return next("/forbidden");
        }
    }

    next();
});

export default router;