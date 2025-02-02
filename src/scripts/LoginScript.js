import axiosClient from "../api/axiosClient";

export default {
    data() {
        return {
            email: "",
            password: "",
            rememberMe: false,
            loading: false,
            error: null,
        };
    },
    methods: {
        async handleLogin() {
            this.loading = true;
            try {
                const response = await axiosClient.post("/login", {
                    email: this.email,
                    password: this.password,
                });

                const result = response.data;

                // Store the token and role in localStorage
                localStorage.setItem("token", result.token);
                localStorage.setItem("role", result.user.role);

                // Redirect based on role
                if (result.user.role === "admin") {
                    this.$router.push("/admin-dashboard");
                } else {
                    this.$router.push("/customer-dashboard");
                }
            } catch (err) {
                this.error = err.response?.data?.message || "Login failed.";
            } finally {
                this.loading = false;
            }
        },
    },
};
