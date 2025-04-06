export default {
    baseURL: import.meta.env.VITE_API_BASE_URL,

    products: {
        getAll: "/DashShoe/products",
        getAllProducts: "/DashShoe/products",
        getById: (id) => `/DashShoe/products/${id}`,
        addToCart: (id) => `/DashShoe/products/${id}/add_to_cart`,
        saveReview: (id) => `/DashShoe/products/${id}/save`,
        search: "/DashShoe/products/search",
        logVisit: "/DashShoe/log-visit"
    },

    auth: {
        register: "/DashShoe/register",
        login: "/DashShoe/login",
        logout: "/DashShoe/logout",
        forgotPassword: "/DashShoe/forgot-password",
        resetPassword: "/DashShoe/reset-password",
    },

    userProfile: {
        profile: "/customer-dashboard",
        favorites: "/DashShoe/profile/favorites",
        ordersHistory: "/customer-dashboard/profile/orders-history",

        /* For API Use URL */
        getProfile: "/DashShoe/profile/getProfile",
        updateProfile: "/DashShoe/profile",
        getOrdersHistory: "/DashShoe/profile/orders",
    },

    favourites: {
        toggle: "/DashShoe/profile/favorites",
        getUserFavourites: "/DashShoe/profile/favorites",
        sync: "/DashShoe/profile/favorites/sync"
    },

    cart: {
        addToCart: "/DashShoe/cart/add",
        getCart: "/DashShoe/cart",
        updateCart: "/DashShoe/cart/update",
        removeFromCart: "/DashShoe/cart/remove"
    },

    refund: {
        store: "/DashShoe/refunds",
        index: "/DashShoe/refunds",
        show: (id) => `/DashShoe/refunds/${id}`,
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
        users: "/DashShoe/admin/users",
        user: (id) => `/DashShoe/admin/users/${id}`,
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
