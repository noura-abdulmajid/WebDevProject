import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axiosClient from "@/services/axiosClient.js";

export default {
    setup() {
        const customer = ref(null);
        const orders = ref([]);
        const loading = ref(true);
        const error = ref(null);
        const route = useRoute();
        const router = useRouter();

        /**
         * Fetches customer details from the backend.
         * If the API fails, displays placeholder data.
         */
        const fetchCustomer = async () => {
            try {
                const response = await axiosClient.get(`/admin/customers/${route.params.id}`);
                customer.value = response.data.customer;
                orders.value = response.data.orders || [];

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

        /**
         * Navigates to the edit customer page.
         */
        const editCustomer = () => {
            if (!customer.value) return;
            router.push(`/admin-customers/edit/${route.params.id}`);
        };

        /**
         * Navigates to the view order page.
         */
        const viewOrder = (orderId) => {
            router.push(`/admin-orders/view/${orderId}`);
        };

        /**
         * Navigates to the edit order page.
         */
        const editOrder = (orderId) => {
            router.push(`/admin-orders/edit/${orderId}`);
        };

        /**
         * Deletes an order from the system.
         */
        const deleteOrder = async (orderId) => {
            if (!confirm("Are you sure you want to delete this order?")) return;
            try {
                await axiosClient.delete(`/admin-orders/${orderId}`);
                orders.value = orders.value.filter(order => order.id !== orderId);
            } catch (err) {
                console.error("Error deleting order:", err);
                alert("Unable to delete order.");
            }
        };

        /**
         * Returns to the customers list.
         */
        const goBack = () => {
            router.push("/admin-customers");
        };

        onMounted(fetchCustomer);

        return { customer, orders, loading, error, editCustomer, viewOrder, editOrder, deleteOrder, goBack };
    },
};
