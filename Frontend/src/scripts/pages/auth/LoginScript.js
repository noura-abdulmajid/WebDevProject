import axios from "@/services/axiosClient.js";
import apiURL from '@/config/apiURL';

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

                this.$router.push(apiURL.userProfile.profile);
            } catch (error) {
                //alert(error.response?.data?.message || "Login failed!");
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
                localStorage.setItem("user_first_name", data.user?.first_name);
                localStorage.setItem("user_last_name", data.user?.surname);
                localStorage.setItem("tel_no", data.user?.tel_no);
                localStorage.setItem("shipping_address", data.user?.shipping_address);
                localStorage.setItem("billing_address", data.user?.billing_address);

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