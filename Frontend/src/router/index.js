
import {createRouter, createWebHistory} from "vue-router";
// Authentication Pages
import Login from "@/components/pages/auth/Login.vue";
import AdminLogin from "@/components/pages/auth/AdminLogin.vue";
import Register from "@/components/pages/auth/Register.vue";
import ForgotPassword from "@/components/pages/auth/ForgotPassword.vue";
import ResetPassword from "@/components/pages/auth/ResetPassword.vue";

// Admin Panel Pages
import Dashboard from "@/components/pages/admin/Dashboard.vue";
import Products from "@/components/pages/admin/Products.vue";
import Customers from "@/components/pages/admin/Customers.vue";
import CustomerProfile from "@/components/pages/admin/CustomerProfile.vue";
import Orders from "@/components/pages/admin/Orders.vue";
import Settings from "@/components/pages/admin/Settings.vue";
import AdminUsers from "@/components/pages/admin/AdminUsers.vue";
import payments from "@/components/pages/admin/refundprocessing.vue";
import shipping from "@/components/pages/admin/shippings.vue";
import SiteReviews from "@/components/pages/admin/SiteReviews.vue";

// User Pages
import ProfileSettings from "@/components/pages/user/ProfileSettings.vue";
import Favorites from "@/components/pages/user/Favorites.vue";
import ProfileDetails from "@/components/pages/user/ProfileDetails.vue";
import OrdersHistory from "@/components/pages/user/OrdersHistory.vue";

// Storefront Pages
import Homepage from "@/components/pages/home/Homepage.vue";
import ContactUs from "@/components/pages/home/ContactUs.vue";
import SiteReview from "@/components/pages/home/SiteReview.vue";
import ChildrenCollection from "@/components/pages/products/ChildrenCollection.vue";
import MenCollection from "@/components/pages/products/MenCollection.vue";
import WomenCollection from "@/components/pages/products/WomenCollection.vue";
import ShoppingCart from "@/components/pages/cart/ShoppingCart.vue";
import Checkout from "@/components/pages/cart/Checkout.vue";

// Maintenance Pages
import Fix from "@/components/pages/maintenance/Fix.vue";
import Forbidden from "@/components/pages/maintenance/Forbidden.vue";

// Layouts
import AdminLayout from "@/components/layouts/AdminLayout.vue";
import HomepageLayout from "@/components/layouts/HomepageLayout.vue";


// Modals
import AdminViewCustomerModal from "@/components/modals/AdminViewCustomerModal.vue";


const routes = [
    {path: "/", redirect: "/Homepage"},

    // Public Pages
    {
        path: "/",
        component: HomepageLayout,
        children: [

            {path: "/Homepage", component: Homepage},
            {path: "/ChildrenCollection", component: ChildrenCollection},
            {path: "/MenCollection", component: MenCollection},
            {path: "/WomenCollection", component: WomenCollection},
            {path: "/ShoppingCart", name: "ShoppingCart", component: ShoppingCart},
            {path: "/Checkout", name: "Checkout", component: Checkout},
            {path: "/contact", component: ContactUs},
            {path: "/site-review", component: SiteReview},

            // Guest Pages (No authentication required)
            {path: "/login", name: "user-login", component: Login, meta: {guestOnly: true}},

            {path: "/register", name: "user-register", component: Register, meta: {guestOnly: true}},
            {path: "/forgot-password", name: "user-forgot-password", component: ForgotPassword, meta: {guestOnly: true}},
            {path: "/reset-password", name: "user-reset-password", component: ResetPassword, meta: {guestOnly: true}},

            // Customer Pages (Require authentication)
            {path: "/customer-dashboard", component: ProfileSettings, meta: {requiresAuth: true, role: "customer"}},
        ],
    },

    // Customer Pages (Require authentication)
    {
        path: "/customer-dashboard",
        name: 'CustomerDashboard',
        component: ProfileSettings,
        meta: {requiresAuth: true, role: "customer"},
        redirect: "/customer-dashboard/profile",
        children: [
            {path: "profile", name: "Profile", component: ProfileDetails},
            {path: "favorites", name: "Favorites", component: Favorites},
            {path: "orders-history", name: "OrdersHistory", component: OrdersHistory},
        ],
    },
    {
        path: "/:pathMatch(.*)*",
        redirect: "/customer-dashboard",
    },

    // Admin Pages (Require authentication) - Using AdminLayout
    {
        path: "/admin",
        component: AdminLayout,
        meta: {requiresAuth: true, role: ["admin", "super_admin"]},
        children: [

            {path: "dashboard", name: "admin-dashboard", component: Dashboard},
            {path: "users", name: "admin-users", component: AdminUsers},
            {path: "products", name: "admin-products", component: Products},
            {path: "orders", name: "admin-orders", component: Orders},
            {path: "settings", name: "admin-settings", component: Settings},
            {path: "customers", name: "admin-customers", component: Customers},
            {path: "customers/:id", name: "admin-customer-profile", component: CustomerProfile},
        ],
    },

    // Forbidden Page
    {path: "/forbidden", component: Forbidden},
    {path: "/fix", component: Fix, meta: {guestOnly: true}},
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("jwt");
    let role = null;

    console.log("Navigating to: " + to.path);
    console.log("Route meta.requiresAuth: " + to.meta.requiresAuth);
    console.log("Token status: " + (token ? "Exists" : "Missing"));

    if (token) {
        try {
            const tokenParts = token.split('.');
            if (tokenParts.length !== 3) {
                throw new Error("Invalid JWT structure, expected 3 parts.");
            }

            const payload = JSON.parse(atob(tokenParts[1])); // 解碼 payload
            console.log("Decoded Payload:", payload);

            if (payload.exp * 1000 > Date.now()) {
                role = payload.role;
            } else {
                console.warn("Token expired. Removing token...");
                localStorage.removeItem("jwt");
                alert("Your session has expired. Please login again.");
            }
        } catch (e) {
            console.error("Invalid JWT token:", e.message);
            localStorage.removeItem("jwt");
        }
    }


    if (to.meta.requiresAuth && !token) {
        alert("Please log in to access this page.");
        return next("/Homepage");
    }
    const noRedirectRoutes = ["/register", "/login", "/forgot-password", "/reset-password", "/contact", "/admin-login"];


    if (to.meta.guestOnly && token && !noRedirectRoutes.includes(to.path)) {
        alert("You do not have permission to access this page.");
        return next("/forbidden");
    }

    next();
});

export default router;