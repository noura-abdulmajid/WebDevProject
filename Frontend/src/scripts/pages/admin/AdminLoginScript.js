import axiosClient from "@/services/axiosClient.js";
import apiConfig from "@/config/apiURL.js";

export default {
    data() {
        return {
            email: "",
            password: "",
            loading: false,
            error: null,
        };
    },
    methods: {
        async handleAdminLogin() {
            this.error = null;
            this.loading = true;

            try {
                const response = await axiosClient.post(apiConfig.admin.login, {
                    email: this.email,
                    password: this.password,
                });

                const result = response.data;

                if (!result.access_token || !this.validateJwt(result.access_token)) {
                    throw new Error("Invalid or missing JWT token in response.");
                }

                localStorage.setItem("jwt", result.access_token);
                localStorage.setItem("token_type", result.token_type);
                localStorage.setItem("admin_info", JSON.stringify(result.admin));
                localStorage.setItem("isAdmin", "true");
                localStorage.setItem("admin_role", result.admin.role);

                axiosClient.defaults.headers.common['Authorization'] = `${result.token_type} ${result.access_token}`;
                this.$router.push({name: "admin-dashboard"});
            } catch (err) {
                console.error("Login failed:", err.message || err);
                this.error = err?.response?.data?.message || "Please try again! Login failed.";

                // Clear all local storage data
                localStorage.removeItem("jwt");
                localStorage.removeItem("token_type");
                localStorage.removeItem("admin_info");
                localStorage.removeItem("isAdmin");
                localStorage.removeItem("admin_role");

                // Clear axios authorization header
                delete axiosClient.defaults.headers.common['Authorization'];

                // Ensure we stay on the login page
                if (this.$route.name !== 'admin-login') {
                    this.$router.push({name: "admin-login"});
                }
            } finally {
                this.loading = false;
            }
        },

        validateJwt(token) {
            if (!token) return false;
            const parts = token.split(".");
            if (parts.length !== 3) return false;

            try {
                const payload = JSON.parse(atob(parts[1]));
                if (!payload.exp) return false;
                const isExpired = payload.exp * 1000 < Date.now();
                if (isExpired) {
                    console.warn("JWT token expired");
                }
                return !isExpired;
            } catch (error) {
                console.error("JWT validation error:", error);
                return false;
            }
        }
    },
};