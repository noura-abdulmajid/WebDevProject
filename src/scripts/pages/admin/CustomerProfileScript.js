import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axiosClient from "@/services/axiosClient.js";
import apiConfig from "@/config/apiURL.js";

export default {
    setup() {
        const customer = ref(null);
        const orders = ref([]);
        const loading = ref(true);
        const error = ref(null);
        const route = useRoute();
        const router = useRouter();
        const showModal = ref(false);
        const editCustomerData = ref({
            first_name: "",
            surname: "",
            email_address: "",
            tel_no: "",
            shipping_address: "",
            billing_address: ""
        });



        /**
         * Fetches customer details from the backend.
         * If the API fails, displays placeholder data.
         */
        const fetchCustomer = async () => {
            try {
                const response = await axiosClient.get(apiConfig.admin.customerProfile(route.params.id));
                customer.value = response.data.customer;
                orders.value = response.data.orders.map(order => ({
                    ...order,
                    itemQuantity: order.ordered_items
                        ? order.ordered_items.reduce((total, item) => total + item.quantity, 0)
                        : 0,
                }));
                //Debug
                //alert(JSON.stringify(customer.value, null, 2));

                if (!customer.value) throw new Error("Customer not found.");
            } catch (err) {
                error.value = "⚠ Unable to retrieve customer data. Displaying placeholder.";
                console.error("Error fetching customer:", err);

                // Display Placeholder Data
                customer.value = {
                    name: "Placeholder Name",
                    email: "example@email.com",
                    phone: "000-000-0000",
                    address: "Not Available",
                    orders: 0,
                    amount_spent: 0.00
                };

                orders.value = [];
            } finally {
                loading.value = false;
            }
        };

        const toggleEditModal = () => {
            showModal.value = !showModal.value;
            if (showModal.value && customer.value) {
                editCustomerData.value = {
                    first_name: customer.value.first_name || "",
                    surname: customer.value.surname || "",
                    email_address: customer.value.email_address || "",
                    tel_no: customer.value.tel_no || "",
                    shipping_address: customer.value.shipping_address || "",
                    billing_address: customer.value.billing_address || ""
                };
                //Debug
                //alert(JSON.stringify(editCustomerData.value, null, 2));
            }
        };

        // Save the edited customer details & send PUT request
        const editCustomer = async () => {
            const { first_name, surname, email_address, tel_no, shipping_address, billing_address } = editCustomerData.value;

            // Validation check (optional)
            if (!first_name || !surname || !email_address) {
                alert('First Name, Surname, and Email are required!');
                return;
            }

            try {
                // Send PUT request to update customer details
                const response = await axiosClient.put(apiConfig.admin.customerProfileEditCustomer(route.params.id), {
                    first_name,
                    surname,
                    email_address,
                    tel_no,
                    shipping_address,
                    billing_address,
                });

                // Show success message and update customer info
                alert('Customer information updated successfully!');
                customer.value = { ...editCustomerData.value }; // Update the customer data
                showModal.value = false; // Close the modal
            } catch (error) {
                console.error('Error updating customer:', error);
                alert('Failed to update customer information. Please try again.');
            }
        };

        /**
         * Navigates to the view order page.
         */
        const viewOrder = (orderId) => {
            router.push(`/admin/orders/view/${orderId}`);
        };

        /**
         * Navigates to the edit order page.
         */
        const editOrder = (orderId) => {
            router.push(`/admin/orders/edit/${orderId}`);
        };

        /**
         * Deletes an order from the system.
         */
        const deleteOrder = async (orderId) => {
            if (!confirm("Are you sure you want to delete this order?")) return;
            try {
                await axiosClient.delete(`/admin/orders/${orderId}`);
                orders.value = orders.value.filter(order => order.O_ID !== orderId);
            } catch (err) {
                console.error("Error deleting order:", err);
                alert("Unable to delete order.");
            }
        };

        /**
         * Returns to the customers list.
         */
        const goBack = () => {
            router.push({ name: "admin-customers" });
        };

        onMounted(fetchCustomer);

        return { customer, orders, loading, error, editCustomer, viewOrder, editOrder, deleteOrder, goBack, toggleEditModal, showModal, editCustomerData };
    },
};
