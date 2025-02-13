import axios from "@/api/axiosClient.js";

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
            this.error = null;

            try {
                const data = await this.login(this.email, this.password);

                this.$router.push("/customer-dashboard");
            } catch (error) {
                alert(error.response?.data?.message || "Login failed!");
            } finally {
                this.loading = false;
            }
        },

        async login(email, password) {
            try {
                const response = await axios.post(
                    "/DashShoe/login",
                    {
                        email,
                        password,
                    });


                const data = response.data;

                if (!data.access_token) {
                    throw new Error("Access token is missing in login response!");
                }

                localStorage.setItem("jwt", data.access_token);
                localStorage.setItem("token_type", data.token_type);
                localStorage.setItem("user_id", data.user?.C_ID);
                localStorage.setItem("user_email", data.user?.email_address);

                return data;
            } catch (error) {
                const status = error.response?.status || "Unknown";

                if ([404, 422, 401].includes(status)) {
                    alert("Invalid username or password. Please try again.");
                } else {
                    alert("Unexpected login error occurred!");
                }

                throw error;
            }
        },
    },
};