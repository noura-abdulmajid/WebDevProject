import axios from "@/services/axiosClient.js";

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
        loadRecaptchaScript() {
            return new Promise((resolve, reject) => {
                if (!document.querySelector('script[src="https://www.google.com/recaptcha/services.js"]')) {
                    const script = document.createElement("script");
                    script.src = "https://www.google.com/recaptcha/api.js";
                    script.async = true;
                    script.defer = true;
                    script.onload = resolve;
                    script.onerror = () => reject("Failed to load reCAPTCHA script.");
                    document.head.appendChild(script);
                } else {
                    resolve();
                }
            });
        },
        async waitForRecaptcha() {
            return Promise.resolve();
            // return new Promise((resolve) => {
            //     const interval = setInterval(() => {
            //         if (window.grecaptcha) {
            //             clearInterval(interval);
            //             resolve();
            //         }
            //     }, 100);
            // });
        },
        async renderRecaptcha() {
            return Promise.resolve();
            // return new Promise((resolve, reject) => {
            //     this.waitForRecaptcha().then(() => {
            //         try {
            //             const container = document.getElementById("recaptcha-container");
            //             if (!container) {
            //                 reject("Recaptcha container not found.");
            //                 return;
            //             }
            //             grecaptcha.render(container, {
            //                 sitekey: "your-site-key",
            //             });
            //             resolve();
            //         } catch (e) {
            //             reject("Failed to render reCAPTCHA.");
            //         }
            //     });
            // });
        },
        async handleForgotPassword() {
            this.error = null;
            this.message = null;
            this.loading = true;

            await this.waitForRecaptcha();
            const fakeRecaptchaResponse = "fake-recaptcha-token";


            //const recaptchaResponse = grecaptcha.getResponse();
            if (!fakeRecaptchaResponse) {
                this.error = "Please complete the reCAPTCHA verification.";
                grecaptcha.reset();
                this.loading = false;
                return;
            }

            try {
                const response = await axios.post("/DashShoe/forgot-password", {
                    email: this.email,
                    recaptcha_token: fakeRecaptchaResponse,
                });

                this.message = response.data.message || "A reset link has been sent to your email.";
            } catch (err) {
                this.error = err.response?.data?.message || "Failed to send reset link.";
                grecaptcha.reset();
            } finally {
                this.loading = false;
            }
        },
    },
    mounted() {
        this.loadRecaptchaScript()
            .then(() => this.renderRecaptcha())
            .catch((err) => console.error(err));
    },
};