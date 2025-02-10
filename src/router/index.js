import { createRouter, createWebHistory } from "vue-router";
import Login from "@/components/Login.vue";
import AdminLogin from "@/components/AdminLogin.vue";
import Fix from "@/components/Fix.vue";
import CreateAccount from "@/components/CreateAccount.vue";
import ForgotPassword from "@/components/ForgotPassword.vue";
import ResetPassword from "@/components/ResetPassword.vue";
import CustomerProfile from "@/components/CustomerProfile.vue";
import AdminDashboard from "@/components/AdminDashboard.vue";
import AdminUsers from "@/components/AdminUsers.vue";
import AdminProducts from "@/components/AdminProducts.vue";
import AdminOrders from "@/components/AdminOrders.vue";
import AdminSettings from "@/components/AdminSettings.vue";
import Forbidden from "@/components/Forbidden.vue";

const routes = [
    { path: "/", redirect: "/login" },

    // ✅ Guest Only Routes (No authentication required)
    { path: "/login", component: Login, meta: { guest: true } },
    { path: "/admin-login", component: AdminLogin, meta: { guest: true } },
    { path: "/fix", component: Fix, meta: { guest: true } },
    { path: "/register", component: CreateAccount, meta: { guest: true } },
    { path: "/forgot-password", component: ForgotPassword, meta: { guest: true } },
    { path: "/reset-password", component: ResetPassword, meta: { guest: true } },

    // ✅ Admin-Only Routes (Protected)
    { path: "/admin-dashboard", component: AdminDashboard, meta: { requiresAuth: true, role: "admin" } },
    { path: "/admin-users", component: AdminUsers, meta: { requiresAuth: true, role: "admin" } },
    { path: "/admin-products", component: AdminProducts, meta: { requiresAuth: true, role: "admin" } },
    { path: "/admin-orders", component: AdminOrders, meta: { requiresAuth: true, role: "admin" } },
    { path: "/admin-settings", component: AdminSettings, meta: { requiresAuth: true, role: "admin" } },

    // ✅ Customer-Only Routes (Protected)
    { path: "/customer-dashboard", component: CustomerProfile, meta: { requiresAuth: true, role: "customer" } },

    // ✅ Forbidden Page
    { path: "/forbidden", component: Forbidden },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// ✅ Navigation Guards for Authentication & Authorization
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("jwt");
    let role = null;

    if (token) {
        try {
            const payload = JSON.parse(atob(token.split(".")[1]));
            if (payload.exp * 1000 > Date.now()) {
                role = payload.role;
            } else {
                localStorage.removeItem("jwt"); // Remove expired token
            }
        } catch (e) {
            console.error("Invalid JWT:", e);
            localStorage.removeItem("jwt");
        }
    }

    // ✅ Redirect if authentication is required but user is not logged in
    if (to.meta.requiresAuth && !role) {
        alert("Please log in to access this page.");
        return next(to.meta.role === "admin" ? "/admin-login" : "/login");
    }

    // ✅ Redirect unauthorized users away from protected pages
    if (to.meta.requiresAuth && to.meta.role !== role) {
        alert("You do not have permission to access this page.");
        return next("/forbidden");
    }

    // ✅ Redirect logged-in users away from guest pages
    const guestPages = ["/register", "/login", "/forgot-password", "/reset-password"];
    if (to.meta.guest && role && !guestPages.includes(to.path)) {
        return next(role === "admin" ? "/admin-dashboard" : "/customer-dashboard");
    }

    next();
});

export default router;
