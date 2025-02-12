import axios from "@/api/axiosClient.js";

export default {
    data() {
        return {
            firstName: "",
            lastName: "",
            email: "",
            message: "",
            loading: false,
            error: "",
            success: "",
        };
    },
    methods: {
        async submitForm() {
            this.loading = true;
            this.error = "";
            this.success = "";

            if (!this.firstName || !this.lastName || !this.email || !this.message) {
                this.error = "All fields are required.";
                alert(this.error);
                this.loading = false;
                return;
            }

            try {
                const headers = {
                    "Content-Type": "application/json",
                };

                const formData = {
                    first_name: this.firstName,
                    last_name: this.lastName,
                    email: this.email,
                    message: this.message,
                };

                const response = await axios.post("/DashShoe/contact-us", formData, { headers });

                if (response.status === 200 && response.data.success) {
                    const successMessage = response.data.message || "Your form has been submitted successfully.";
                    alert(successMessage);
                    this.success = successMessage;
                    this.resetForm();
                } else {
                    const errorMessage = response.data.message || "Unexpected server response.";
                    alert(errorMessage);
                    this.error = errorMessage;
                }
            } catch (e) {
                if (e.response) {
                    if (e.response.status === 422) {
                        const validationMessage = "Validation failed. Please check your input.";
                        alert(validationMessage);
                        this.error = validationMessage;
                        this.validationErrors = e.response.data.errors || {};
                    } else if (e.response.status === 500) {
                        const serverErrorMessage = e.response.data.message || "A server error occurred. Please try again later.";
                        alert(serverErrorMessage);
                        this.error = serverErrorMessage;
                    } else {
                        const genericErrorMessage = e.response.data.message || "An error occurred. Please try again.";
                        alert(genericErrorMessage);
                        this.error = genericErrorMessage;
                    }
                } else {
                    const networkErrorMessage = "A network error occurred. Please check your connection and try again.";
                    alert(networkErrorMessage);
                    this.error = networkErrorMessage;
                }
            } finally {
                this.loading = false;
            }
        },
        resetForm() {
            this.firstName = "";
            this.lastName = "";
            this.email = "";
            this.message = "";
        },
    },
};