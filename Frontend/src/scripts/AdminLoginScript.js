import axiosClient from "../api/axiosClient";

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
                const response = await axiosClient.post("/DashShoe/admin/login", {
                    email: this.email,
                    password: this.password,
                });

                const result = response.data;

                localStorage.setItem("jwt", result.token);
                localStorage.setItem("token_type", result.token_type);
                localStorage.setItem("admin_info", JSON.stringify(result.admin));

                this.$router.push("/admin-dashboard");
            } catch (err) {
                this.error = err.response?.data?.message || "Admin login failed.";
            } finally {
                this.loading = false;
            }
        },
    },
};