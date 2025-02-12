import { createRouter, createWebHistory } from "vue-router";
import Login from "@/components/Login.vue";
import AdminLogin from "@/components/AdminLogin.vue";
import Fix from "@/components/Fix.vue";
import CreateAccount from "@/components/CreateAccount.vue";
import ForgotPassword from "@/components/ForgotPassword.vue";
import ResetPassword from "@/components/ResetPassword.vue";
import CustomerProfile from "@/components/CustomerProfile.vue";
import AdminDashboard from "@/components/AdminDashboard.vue";
import ContactUs from "@/components/ContactUs.vue";
import SiteReview from "@/components/SiteReview.vue";
import AdminUsers from "@/components/AdminUsers.vue";
import AdminProducts from "@/components/AdminProducts.vue";
import AdminOrders from "@/components/AdminOrders.vue";
import AdminSettings from "@/components/AdminSettings.vue";
import Forbidden from "@/components/Forbidden.vue";
import Homepage from "@/components/Homepage.vue";
import ChildrenCollection from "@/components/ChildrenCollection.vue";
import MenCollection from "@/components/MenCollection.vue";
import WomenCollection from "@/components/WomenCollection.vue";

const routes = [
    { path: "/", redirect: "/Homepage" },
    { path: "/Homepage", component: Homepage },
    { path: "/ChildrenCollection", component: ChildrenCollection },
    { path: "/MenCollection", component: MenCollection },
    { path: "/WomenCollection", component: WomenCollection },
    { path: "/login", component: Login , meta: { guestOnly: true }},
    { path: "/fix", component: Fix , meta: { guestOnly: true }},
    { path: "/register", component: CreateAccount, meta: { guestOnly: true } },
    { path: "/forgot-password", component: ForgotPassword , meta: { guestOnly: true }},
    { path: "/reset-password", component: ResetPassword , meta: { guestOnly: true }},
    { path: "/admin-dashboard", component: AdminDashboard, meta: { requiresAuth: true } },
    { path: "/customer-dashboard", component: CustomerProfile, meta: { requiresAuth: true } },
    { path: "/contact", component: ContactUs },
    { path: "/site-review", component: SiteReview },
    { path: "/forbidden", component: Forbidden },
    { path: "/admin-login", component: AdminLogin, meta: { guest: true } },

    { path: "/admin-dashboard", component: AdminDashboard, meta: { requiresAuth: true, role: "admin" } },
    { path: "/admin-users", component: AdminUsers, meta: { requiresAuth: true, role: "admin" } },
    { path: "/admin-products", component: AdminProducts, meta: { requiresAuth: true, role: "admin" } },
    { path: "/admin-orders", component: AdminOrders, meta: { requiresAuth: true, role: "admin" } },
    { path: "/admin-settings", component: AdminSettings, meta: { requiresAuth: true, role: "admin" } },

    { path: "/customer-dashboard", component: CustomerProfile, meta: { requiresAuth: true, role: "customer" } },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("jwt");
    let role = null;

    if (token) {
        try {
            const payload = JSON.parse(atob(token.split('.')[1]));
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

    if (to.meta.requiresAuth && !token) {
        alert("Please log in to access this page.");
        return next("/Homepage");
    }
    const noRedirectRoutes = ["/register", "/login", "/forgot-password", "/reset-password","/contact"];


    if (to.meta.guestOnly && token && !noRedirectRoutes.includes(to.path)) {
        alert("You do not have permission to access this page.");
        return next("/forbidden");
    }

    next();
});

export default router;