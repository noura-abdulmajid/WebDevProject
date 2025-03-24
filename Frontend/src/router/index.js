import { createRouter, createWebHistory } from "vue-router";
import Login from "@/components/Login.vue";
import AdminLogin from "@/components/AdminLogin.vue";
import Fix from "@/components/Fix.vue";
import CreateAccount from "@/components/CreateAccount.vue";
import ForgotPassword from "@/components/ForgotPassword.vue";
import ResetPassword from "@/components/ResetPassword.vue";
import AdminDashboard from "@/components/AdminDashboard.vue";
import ContactUs from "@/components/ContactUs.vue";
import SiteReview from "@/components/SiteReview.vue";
import AdminUsers from "@/components/AdminUsers.vue";
import AdminProducts from "@/components/AdminProducts.vue";
import AdminCustomers from "@/components/AdminCustomers.vue";
import AdminCustomerProfile from "@/components/AdminCustomerProfile.vue";
import AdminOrders from "@/components/AdminOrders.vue";
import AdminSettings from "@/components/AdminSettings.vue";
import Forbidden from "@/components/Forbidden.vue";
import Homepage from "@/components/Homepage.vue";
import ChildrenCollection from "@/components/ChildrenCollection.vue";
import MenCollection from "@/components/MenCollection.vue";
import WomenCollection from "@/components/WomenCollection.vue";
import ShoppingCart from "@/components/ShoppingCart.vue";
import Checkout from "@/components/Checkout.vue";
import sustainability from "@/components/sustainabilty.vue";
import newarrivals from "@/components/newarrivals.vue";
// Import ProductDetail component (uncommented)
import ProductDetail from "@/components/productview.vue";
// ✅ Customer Pages (Require Authentication)
import CustomerProfile from "@/components/CustomerProfile.vue";
import Newpage from "@/components/newpage.vue";
import HomepageLayout from "@/components/HomepageLayout.vue";

const routes = [
    // Public Pages
    {
        path: "/",
        component: HomepageLayout,
        children: [
            { path: "/Newpage", component: Newpage },
            { path: "/Homepage", component: Homepage },
            { path: "/ChildrenCollection", component: ChildrenCollection },
            { path: "/MenCollection", component: MenCollection },
            { path: "/WomenCollection", component: WomenCollection },
            { path: "/ShoppingCart", name: "ShoppingCart", component: ShoppingCart },
            { path: "/Checkout", name: "Checkout", component: Checkout },
            // Added Product Detail route as a child route
            { path: "/product/:id", name: "ProductDetail", component: ProductDetail, props: true },
        ],
    },

    { path: "/login", component: Login, meta: { guestOnly: true } },
    { path: "/fix", component: Fix, meta: { guestOnly: true } },
    { path: "/register", component: CreateAccount, meta: { guestOnly: true } },
    { path: "/forgot-password", component: ForgotPassword, meta: { guestOnly: true } },
    { path: "/reset-password", component: ResetPassword, meta: { guestOnly: true } },
    { path: "/admin-dashboard", component: AdminDashboard, meta: { requiresAuth: true } },
    { path: "/customer-dashboard", component: CustomerProfile, meta: { requiresAuth: true } },
    { path: "/contact", component: ContactUs },
    { path: "/site-review", component: SiteReview },
    { path: "/forbidden", component: Forbidden },
    { path: "/sustainability", component: sustainability },
    { path: "/newarrivals", component: newarrivals },
    { path: "/admin-login", component: AdminLogin, meta: { guest: true } },
    { path: "/admin-dashboard", component: AdminDashboard, meta: { requiresAuth: true, role: ["admin", "super_admin"] } },
    { path: "/admin-users", component: AdminUsers, meta: { requiresAuth: true, role: ["admin", "super_admin"] } },
    { path: "/admin-products", component: AdminProducts, meta: { requiresAuth: true, role: ["admin", "super_admin"] } },
    { path: "/admin-orders", component: AdminOrders, meta: { requiresAuth: true, role: ["admin", "super_admin"] } },
    { path: "/admin-settings", component: AdminSettings, meta: { requiresAuth: true, role: ["admin", "super_admin"] } },
    { path: "/admin-customers", component: AdminCustomers, meta: { requiresAuth: true, role: ["admin", "super_admin"] } },
    { path: "/admin-customers/:id", component: AdminCustomerProfile, meta: { requiresAuth: true, role: ["admin", "super_admin"] } },
    { path: "/customer-dashboard", component: CustomerProfile, meta: { requiresAuth: true, role: "customer" } },
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
            const tokenParts = token.split(".");
            if (tokenParts.length !== 3) {
                throw new Error("Invalid JWT structure, expected 3 parts.");
            }
            const payload = JSON.parse(atob(tokenParts[1])); // Decode payload
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
    const noRedirectRoutes = ["/register", "/login", "/forgot-password", "/reset-password", "/contact"];

    if (to.meta.guestOnly && token && !noRedirectRoutes.includes(to.path)) {
        alert("You do not have permission to access this page.");
        return next("/forbidden");
    }

    next();
});

export default router;
