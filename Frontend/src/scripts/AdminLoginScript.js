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

                if (!result.access_token || !this.validateJwt(result.access_token)) {
                    throw new Error("Invalid or missing JWT token in response.");
                }

                localStorage.setItem("jwt", result.access_token);
                localStorage.setItem("token_type", result.token_type);
                localStorage.setItem("admin_info", JSON.stringify(result.admin));

                this.$router.push("/admin-dashboard");
            } catch (err) {
                console.error("Login failed:", err.message || err);
                alert(err?.response?.data?.message || "Please try again! login failed.");
                localStorage.clear();
                this.$router.push("/admin-login");
            } finally {
                this.loading = false;
            }
        },

        validateJwt(token) {
            if (!token || token.split(".").length !== 3) {
                console.error("Invalid JWT structure:", token);
                return false;
            }
            try {
                const payload = JSON.parse(atob(token.split(".")[1]));
                return payload.exp * 1000 > Date.now();
            } catch (error) {
                console.error("Error parsing JWT:", error);
                return false;
            }
        },
    },
};