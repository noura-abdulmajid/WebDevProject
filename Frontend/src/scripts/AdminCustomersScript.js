import { ref, computed, onMounted } from "vue";
import axiosClient from "../api/axiosClient";
import { useRouter } from "vue-router";

export default {
    setup() {
        const customers = ref([]);
        const searchQuery = ref("");
        const activeActionMenu = ref(null);
        const loading = ref(true);
        const error = ref(null);
        const router = useRouter();

        // Retrieve JWT token
        const token = localStorage.getItem("jwt");

        // Fetch customers from server
        const fetchCustomers = async () => {
            try {
                loading.value = true;
                const response = await axiosClient.get("http://127.0.0.1:8000/api/DashShoe/admin/get_users", {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${token}`,
                    },
                });

                const users = response.data.users.map((user) => ({
                    id: user.C_ID,
                    name: `${user.first_name} ${user.surname}`,
                    email: user.email_address,
                    phone: user.tel_no || "N/A",
                    shippingAddress: user.shipping_address,
                    orders: 0,
                    amount_spent: 0.0,
                }));

                customers.value = users;
                localStorage.setItem("customers", JSON.stringify(users));
            } catch (err) {
                error.value = "⚠ Failed to load customers. Displaying last available data.";
                console.error("Error fetching customers:", err);
                customers.value = JSON.parse(localStorage.getItem("customers")) || [];
            } finally {
                loading.value = false;
            }
        };

        // Toggle action menu
        const toggleActionMenu = (customerId) => {
            activeActionMenu.value = activeActionMenu.value === customerId ? null : customerId;
        };

        // View customer details
        const viewCustomer = (customerId) => {
            router.push(`/admin-customers/${customerId}`);
        };

        // Edit customer data
        const editCustomer = (customerId) => {
            router.push(`/admin-customers/edit/${customerId}`);
        };

        // Delete customer
        const deleteCustomer = async (customerId) => {
            if (!confirm("Are you sure you want to delete this customer?")) return;

            try {
                await axiosClient.delete(`/admin/customers/${customerId}`, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${token}`,
                    },
                });
                fetchCustomers();
            } catch (err) {
                console.error("Error deleting customer:", err);
            }
        };

        // Filter customers based on the search query
        const filteredCustomers = computed(() => {
            return customers.value.filter((customer) =>
                customer.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                customer.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                customer.phone.toString().toLowerCase().includes(searchQuery.value.toLowerCase())
            );
        });

        // Fetch customers on component mount
        onMounted(fetchCustomers);

        return {
            customers,
            searchQuery,
            activeActionMenu,
            loading,
            error,
            fetchCustomers,
            toggleActionMenu,
            viewCustomer,
            editCustomer,
            deleteCustomer,
            filteredCustomers,
        };
    },
};