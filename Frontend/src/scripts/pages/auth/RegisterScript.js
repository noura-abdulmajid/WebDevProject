import axios from "@/services/axiosClient.js";

export default {
    data() {
        return {
            firstName: "",
            surname: "",
            email: "",
            password: "",
            confirmPassword: "",
            emailProvider: "",
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

            try {
                const fullEmail = this.emailName + this.emailProvider;

                const registrationData = {
                    first_name: this.firstName,
                    surname: this.surname,
                    email_address: fullEmail,
                    password: this.password,
                    date_joined: new Date().toISOString(),
                };
                //Debug
                //alert(JSON.stringify(registrationData));

                const response = await axios.post("/DashShoe/register", registrationData);

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