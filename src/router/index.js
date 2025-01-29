import { createRouter, createWebHistory } from "vue-router";
import Login from "@/components/Login.vue";
import CustomerProfile from "@/components/CustomerProfile.vue";
import AdminDashboard from "@/components/AdminDashboard.vue";

const routes = [
    { path: "/", redirect: "/login" },
    { path: "/login", component: Login },
    { path: "/admin-dashboard", component: AdminDashboard, meta: { requiresAuth: true, role: "admin" } },
    { path: "/customer-dashboard", component: CustomerProfile, meta: { requiresAuth: true, role: "customer" } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// ✅ **Navigation Guards for Authentication & Authorization**
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token"); // Check if user is logged in
    const role = localStorage.getItem("role"); // Get stored role

    if (to.meta.requiresAuth) {
        if (!token) return next("/login"); // Redirect if not logged in
        if (to.meta.role && to.meta.role !== role) return next("/login"); // Prevent role mismatch
    }

    next();
});

export default router;
