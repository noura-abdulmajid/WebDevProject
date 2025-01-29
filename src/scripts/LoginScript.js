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
                const response = await fetch("https://api.example.com/login", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        email: this.email,
                        password: this.password,
                    }),
                });

                const result = await response.json();

                if (!response.ok) throw new Error(result.error || "Login failed");

                // Store token and role
                localStorage.setItem("token", result.token);
                localStorage.setItem("role", result.user.role);

                // Redirect based on role
                if (result.user.role === "admin") {
                    this.$router.push("/admin-dashboard");
                } else {
                    this.$router.push("/customer-dashboard");
                }
            } catch (err) {
                this.error = err.message;
            } finally {
                this.loading = false;
            }
        },
    },
};
