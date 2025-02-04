import axiosClient from "../api/axiosClient";

export default {
    data() {
        return {
            email: "",
            loading: false,
            error: null,
            message: null,
        };
    },
    methods: {
        async handleForgotPassword() {
            this.error = null;
            this.message = null;
            this.loading = true;

            // Validate reCAPTCHA
            const recaptchaResponse = grecaptcha.getResponse(); // Get reCAPTCHA response
            if (!recaptchaResponse) {
                this.error = "Please complete the reCAPTCHA verification.";
                this.loading = false;
                return;
            }

            try {
                const response = await axiosClient.post("/forgot-password", {
                    email: this.email,
                    recaptcha_token: recaptchaResponse, // Send reCAPTCHA token
                });

                this.message = response.data.message || "A reset link has been sent to your email.";
            } catch (err) {
                this.error = err.response?.data?.message || "Failed to send reset link.";
            } finally {
                this.loading = false;
            }
        },
    },
};
