import axiosClient from "../api/axiosClient";

export default {
    data() {
        return {
            token: "",
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
                const response = await axiosClient.post("/reset-password", {
                    token: this.token,
                    password: this.password,
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
