export default {
    baseURL: import.meta.env.VITE_API_BASE_URL,

    auth: {
        register: "/DashShoe/register",
        login: "/DashShoe/login",
        logout: "/DashShoe/logout",
        forgotPassword: "/DashShoe/forgot-password",
        resetPassword: "/DashShoe/reset-password",
    },

    userProfile: {

        profile: "/customer-dashboard",
        favorites: "/customer-dashboard/profile/favorites",
        ordersHistory: "/customer-dashboard/profile/orders-history",

        /* For API Use URL */
        getProfile: "/DashShoe/profile/getProfile",
        updateProfile: "/DashShoe/profile",
        getOrdersHistory: "/DashShoe/profile/order",
    },

    admin: {
        login: "/DashShoe/admin/login",
        logout: "/DashShoe/admin/logout",
        customers: "/DashShoe/admin/get_users",
        customerProfile: (id) => `/DashShoe/admin/customers/${id}`,
        customerProfileEditCustomer: (id) => `/DashShoe/admin/customers/${id}`,
        customerProfileEditOrder: (id) => `/DashShoe/admin/orders/${id}`,
        customerProfileDeleteOrder: (id) => `/DashShoe/admin/orders/${id}`,
        createUser: "/DashShoe/admin/create_user",
        dashboardStats: "/DashShoe/admin/dashboard_status",
        getUser: (id) => `/DashShoe/admin/get_user/${id}`,
        updateUser: (id) => `/DashShoe/admin/update_user/${id}`,
        deleteUser: (id) => `/DashShoe/admin/delete_user/${id}`,
        test: "/DashShoe/admin/test",
    },

    adminProducts: {
        getProducts: "/DashShoe/admin/get_products",
    },

    checkout: {
        process: "/DashShoe/checkout",
    },

    contact: {
        sendMessage: "/DashShoe/contact-us",
        create: "/contact",
        confirm: "/contact_confirm",
    },

    siteReview: {
        createReview: "/DashShoe/site-review",
        create: "/site_review",
        confirm: "/site_review_confirm",
    },

    logs: {
        visit: "/DashShoe/log-visit",
    },

    testRoute: "/DashShoe/test",

    dbTest: "/db-test",
};
