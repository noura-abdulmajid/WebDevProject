import { registerUser } from "../api/authService";

export default {
    data() {
        return {
            name: "",
            email: "",
            password: "",
            confirmPassword: "",
            loading: false,
            error: null,
            success: null,
        };
    },
    methods: {
        async handleRegister() {
            this.error = null;
            this.success = null;

            // Validate Password Match
            if (this.password !== this.confirmPassword) {
                this.error = "Passwords do not match.";
                return;
            }

            this.loading = true;

            try {
                await registerUser({
                    name: this.name,
                    email: this.email,
                    password: this.password,
                });

                this.success = "Your account has been created successfully!";
                setTimeout(() => {
                    this.$router.push("/login"); // Redirect to login page after success
                }, 2000);
            } catch (err) {
                this.error = err.response?.data?.message || "Registration failed.";
            } finally {
                this.loading = false;
            }
        },
    },
};
