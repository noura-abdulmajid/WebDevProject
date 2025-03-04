import axios from "@/services/axiosClient.js";

export default {
    data() {
        return {
            formData: {
                firstName: "",
                lastName: "",
                email: "",
                message: "",
            },
            loading: false,
            error: "",
            success: "",
        };
    },
    mounted() {
        if (!this.formData || !this.formData.firstName) {
            this.resetForm();
        }
    },
    methods: {
        async submitForm() {
            this.loading = true;
            this.error = "";
            this.success = "";

            if (!this.validateForm()) {
                this.handleError("All fields are required.");
                return;
            }

            try {
                const response = await axios.post("/DashShoe/contact-us", this.formatFormData(), {
                    headers: { "Content-Type": "application/json" },
                });

                if (response.status === 200 && response.data.success) {
                    this.handleSuccess(response.data.message || "Your form has been submitted successfully.");
                } else {
                    this.handleError(response.data.message || "Unexpected server response.");
                }
            } catch (e) {
                this.handleRequestError(e);
            } finally {
                this.loading = false;
            }
        },
        validateForm() {
            const { firstName, lastName, email, message } = this.formData;
            return firstName && lastName && email && message;
        },
        formatFormData() {
            const { firstName, lastName, email, message } = this.formData;
            return {
                first_name: firstName,
                last_name: lastName,
                email: email,
                message: message,
            };
        },
        handleSuccess(message) {
            alert(message);
            this.success = message;
            this.resetForm();
        },
        handleError(message) {
            alert(message);
            this.error = message;
            this.loading = false;
        },
        handleRequestError(e) {
            if (e.response) {
                const status = e.response.status;
                const errorMessage = e.response.data.message || "An error occurred. Please try again.";
                switch (status) {
                    case 422:
                        this.handleError("Validation failed. Please check your input.");
                        break;
                    case 500:
                        this.handleError(errorMessage || "A server error occurred. Please try again later.");
                        break;
                    default:
                        this.handleError(errorMessage);
                        break;
                }
            } else {
                this.handleError("A network error occurred. Please check your connection and try again.");
            }
        },
        resetForm() {
            this.formData = {
                firstName: "",
                lastName: "",
                email: "",
                message: "",
            };
        },
    },
};