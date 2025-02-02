import { createRouter, createWebHistory } from "vue-router";
import Login from "../components/Login.vue";
import CreateAccount from "../components/CreateAccount.vue";
import CustomerProfile from "../components/CustomerProfile.vue";
import AdminDashboard from "../components/AdminDashboard.vue";

const routes = [
    { path: "/", redirect: "/login" },
    { path: "/login", component: Login, meta: { guestOnly: true } },
    { path: "/register", component: CreateAccount, meta: { guestOnly: true } },
    { path: "/admin-dashboard", component: AdminDashboard, meta: { requiresAuth: true, role: "admin" } },
    { path: "/customer-dashboard", component: CustomerProfile, meta: { requiresAuth: true, role: "customer" } },
    { path: "/forbidden", component: () => import("../components/Forbidden.vue") }, // New Forbidden Page
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// ✅ **Navigation Guards for Authentication & Authorization**
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token");
    const role = localStorage.getItem("role");

    // ✅ Redirect logged-in users away from login/register pages
    if (token && to.meta.guestOnly) {
        return next(role === "admin" ? "/admin-dashboard" : "/customer-dashboard");
    }

    // ✅ Secure protected routes
    if (to.meta.requiresAuth) {
        if (!token) return next("/login"); // Redirect if not logged in
        if (to.meta.role && to.meta.role !== role) return next("/forbidden"); // Redirect unauthorized users
    }

    next();
});

export default router;
