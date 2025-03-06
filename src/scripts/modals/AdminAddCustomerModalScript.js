import { ref, defineProps, defineEmits } from "vue";
import axiosClient from "@/services/axiosClient.js";
import apiConfig from "@/config/apiURL.js";

export default {
    props: {
        isVisible: Boolean, //  Controls modal visibility
    },
    emits: ["close", "customerAdded"], //  Emits success and close events

    setup(props, { emit }) {
        const newCustomer = ref({
            firstName: "",
            surname: "",
            emailName: "",
            emailProvider: "@gmail.com",
            phone: "",
            password: "",
            confirmPassword: "",
        });

        /**
         *  Submits new customer data.
         * - Validates all fields before submitting.
         * - Ensures passwords match.
         * - Auto-closes modal on success.
         */
        const submitCustomer = async () => {
            //  Validate required fields
            if (!newCustomer.value.firstName || !newCustomer.value.surname || !newCustomer.value.emailName || !newCustomer.value.phone || !newCustomer.value.password) {
                alert("Please fill in all required fields.");
                return;
            }

            //  Check if passwords match
            if (newCustomer.value.password !== newCustomer.value.confirmPassword) {
                alert("Passwords do not match.");
                return;
            }

            try {
                const fullEmail = newCustomer.value.emailName.trim() + newCustomer.value.emailProvider;
                await axiosClient.post(apiConfig.auth.register, {
                    first_name: newCustomer.value.firstName,
                    surname: newCustomer.value.surname,
                    email_address: fullEmail,
                    phone: newCustomer.value.phone,
                    password: newCustomer.value.password,
                });

                alert("Customer account created successfully!");
                emit("customerAdded"); //  Notify parent component to refresh list
                closeModal(); //  Auto-close modal after successful submission
            } catch (err) {
                console.error("Error adding customer:", err);
                alert("Failed to add customer.");
            }
        };

        /**
         *  Closes the modal.
         */
        const closeModal = () => {
            emit("close");
        };

        return { newCustomer, submitCustomer, closeModal };
    },
};
