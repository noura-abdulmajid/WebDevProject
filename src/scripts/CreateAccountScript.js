import axios from "@/api/axiosClient.js";

export default {
    data() {
        return {
            firstName: "",
            surname: "",
            emailName: "", //Only Email Name input
            emailProvider: "@gmail.com", // Default provider
            password: "",
            confirmPassword: "",
            /*telNo: "",
            shippingAddress: "",
            billingAddress: "",*/ //Decrease Fills
            loading: false,
            error: "",
            success: "",
        };
    },
    methods: {
        async handleRegister() {
            this.loading = true;
            this.error = "";
            this.success = "";

            if (this.password !== this.confirmPassword) {
                this.error = "Passwords do not match.";
                this.loading = false;
                return;
            }

            // Combine email name & provider
            const fullEmail = this.emailName.trim() + this.emailProvider;

            try {
                const registrationData = {
                    first_name: this.firstName,
                    surname: this.surname,
                    email_address: fullEmail,
                    password: this.password,
                    /*tel_no: this.telNo,
                    shipping_address: this.shippingAddress,
                    billing_address: this.billingAddress,*/
                    date_joined: new Date().toISOString(),
                };

                const response = await axios.post("/api/DashShoe/register", registrationData);

                if (response.status === 201 && response.data.message === "Customer registered successfully") {
                    alert("Customer registered successfully!");
                    this.$router.push("/login");
                } else {
                    this.error = "Unexpected server response.";
                }

                this.loading = false;
            } catch (e) {
                this.error = e.response?.data?.message || "An error occurred.";
                this.loading = false;
            }
        },
    },
};