import axios from "../api/axiosClient.js";

export default {
    data() {
        return {
            token: "",
            email: "",
            password: "",
            confirmPassword: "",
            loading: false,
            error: null,
            message: null,
        };
    },
    mounted() {
        // Extract token from the URL
        const urlParams = new URLSearchParams(window.location.search);
        this.token = urlParams.get("token");
        this.email = urlParams.get("email");

    },
    methods: {
        async handlePasswordReset() {
            this.error = null;
            this.message = null;
            this.loading = true;

            if (this.password !== this.confirmPassword) {
                this.error = "Passwords do not match.";
                this.loading = false;
                return;
            }

            try {
                const response = await axios.post("/api/DashShoe/reset-password", {
                    email: this.email,
                    token: this.token,
                    password: this.password,
                    password_confirmation: this.confirmPassword,
                });

                this.message = response.data.message || "Your password has been reset.";
                setTimeout(() => {
                    this.$router.push("/login");
                }, 2000);
            } catch (err) {
                this.error = err.response?.data?.message || "Failed to reset password.";
            } finally {
                this.loading = false;
            }
        },
    },
};