import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../api/axiosClient";

export default {
    setup() {
        const route = useRoute();
        const customer = ref(null);
        const orders = ref([]);
        const loading = ref(true);
        const error = ref(null);

        // ✅ Fetch Customer Details
        const fetchCustomer = async () => {
            try {
                const response = await axiosClient.get(`/admin/customers/${route.params.id}`);
                customer.value = response.data;
            } catch (err) {
                error.value = "⚠ Failed to load customer details.";
                console.error("Error fetching customer:", err);
            }
        };

        // ✅ Fetch Customer's Order History
        const fetchOrders = async () => {
            try {
                const response = await axiosClient.get(`/admin/customers/${route.params.id}/orders`);
                orders.value = response.data.orders;
            } catch (err) {
                console.error("Error fetching order history:", err);
            }
        };

        // ✅ Update Customer Information
        const updateCustomer = async () => {
            try {
                await axiosClient.put(`/admin/customers/${route.params.id}`, customer.value);
                alert("Customer details updated successfully!");
            } catch (err) {
                console.error("Error updating customer:", err);
            }
        };

        // ✅ Format Date
        const formatDate = (dateString) => {
            const date = new Date(dateString);
            return date.toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" });
        };

        onMounted(async () => {
            await fetchCustomer();
            await fetchOrders();
            loading.value = false;
        });

        return { customer, orders, loading, error, updateCustomer, formatDate };
    },
};
