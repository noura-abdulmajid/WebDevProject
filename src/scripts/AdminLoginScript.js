import axiosClient from "../api/axiosClient";

export default {
    data() {
        return {
            email: "",         // Stores admin's email input
            password: "",      // Stores admin's password input
            loading: false,    // Shows loading state during login
            error: null,       // Stores error messages for invalid logins
        };
    },
    methods: {
        async handleAdminLogin() {
            this.error = null;      // Clear previous errors
            this.loading = true;    // Show loading indicator

            try {
                const response = await axiosClient.post("/admin-login", {
                    email: this.email,
                    password: this.password,
                });

                const result = response.data;
                localStorage.setItem("token", result.token); // Store JWT token
                localStorage.setItem("role", "admin");       // Mark user as admin

                this.$router.push("/admin-dashboard");       // Redirect to Admin Dashboard
            } catch (err) {
                this.error = err.response?.data?.message || "Admin login failed.";
            } finally {
                this.loading = false;  // Hide loading indicator
            }
        },
    },
};

