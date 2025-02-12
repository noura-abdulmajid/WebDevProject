import { createRouter, createWebHistory } from "vue-router";

// ✅ Guest Pages (No authentication required)
import Login from "@/components/Login.vue";
import AdminLogin from "@/components/AdminLogin.vue";
import Fix from "@/components/Fix.vue";
import CreateAccount from "@/components/CreateAccount.vue";
import ForgotPassword from "@/components/ForgotPassword.vue";
import ResetPassword from "@/components/ResetPassword.vue";

// ✅ Admin Pages (Require Authentication)
import AdminDashboard from "@/components/AdminDashboard.vue";
import AdminCustomers from "@/components/AdminCustomers.vue";
import AdminCustomerProfile from "@/components/AdminCustomerProfile.vue";
import AdminProducts from "@/components/AdminProducts.vue";
import AdminOrders from "@/components/AdminOrders.vue";
import AdminSettings from "@/components/AdminSettings.vue";

// ✅ Customer Pages (Require Authentication)
import CustomerProfile from "@/components/CustomerProfile.vue";

// ✅ Forbidden Page
import Forbidden from "@/components/Forbidden.vue";

const routes = [
    { path: "/", redirect: "/login" },

    // ✅ Guest Routes (No authentication required)
    { path: "/login", component: Login, meta: { guest: true } },
    { path: "/admin-login", component: AdminLogin, meta: { guest: true } },
    { path: "/fix", component: Fix, meta: { guest: true } },
    { path: "/register", component: CreateAccount, meta: { guest: true } },
    { path: "/forgot-password", component: ForgotPassword, meta: { guest: true } },
    { path: "/reset-password", component: ResetPassword, meta: { guest: true } },

    // ✅ Admin Routes (Require Authentication - Change `guest: true` later)
    { path: "/admin-dashboard", component: AdminDashboard, meta: { guest: true } }, //meta: { requiresAuth: true, role: "admin" } },
    { path: "/admin-customers", component: AdminCustomers, meta: { guest: true } }, //meta: { requiresAuth: true, role: "admin" } },
    { path: "/admin-customers/:id", component: AdminCustomerProfile, meta: { guest: true } }, //meta: { requiresAuth: true, role: "admin" } },// Customer Detail Page
    { path: "/admin-products", component: AdminProducts, meta: { guest: true } },//meta: { requiresAuth: true, role: "admin" } },
    { path: "/admin-orders", component: AdminOrders, meta: { guest: true } },//meta: { requiresAuth: true, role: "admin" } },
    { path: "/admin-settings", component: AdminSettings, meta: { guest: true } },//meta: { requiresAuth: true, role: "admin" } },

    // ✅ Customer Routes (Require Authentication)
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
    let userRole = null;

    if (token) {
        try {
            const payload = JSON.parse(atob(token.split(".")[1])); // Decode JWT payload
            if (payload.exp * 1000 > Date.now()) {
                userRole = payload.role; // Set user role if token is valid
            } else {
                localStorage.removeItem("jwt"); // Remove expired token
            }
        } catch (error) {
            console.error("Invalid JWT:", error);
            localStorage.removeItem("jwt");
        }
    }

    // ✅ Redirect if authentication is required but user is not logged in
    if (to.meta.requiresAuth && !userRole) {
        alert("Please log in to access this page.");
        return next(to.meta.role === "admin" ? "/admin-login" : "/login");
    }

    // ✅ Redirect unauthorized users away from protected pages
    if (to.meta.requiresAuth && to.meta.role !== userRole) {
        alert("You do not have permission to access this page.");
        return next("/forbidden");
    }

    // ✅ Redirect logged-in users away from guest pages
    const guestPages = ["/register", "/login", "/forgot-password", "/reset-password"];
    if (to.meta.guest && userRole && !guestPages.includes(to.path)) {
        return next(userRole === "admin" ? "/admin-dashboard" : "/customer-dashboard");
    }

    next();
});

export default router;
