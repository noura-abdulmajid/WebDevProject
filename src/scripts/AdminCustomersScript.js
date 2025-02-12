import { ref, computed, onMounted } from "vue";
import axiosClient from "../api/axiosClient";
import { useRouter } from "vue-router";

export default {
    setup() {
        const customers = ref(JSON.parse(localStorage.getItem("customers")) || []);
        const searchQuery = ref("");
        const activeActionMenu = ref(null);
        const loading = ref(true);
        const error = ref(null);
        const router = useRouter();

        // ✅ Fetch Customers from Backend
        const fetchCustomers = async () => {
            try {
                const response = await axiosClient.get(`/admin/customers?search=${searchQuery.value}`);
                customers.value = response.data.customers;
                localStorage.setItem("customers", JSON.stringify(response.data.customers));
            } catch (err) {
                error.value = "⚠ Failed to load customers. Displaying last available data.";
                console.error("Error fetching customers:", err);
            } finally {
                loading.value = false;
            }
        };

        // ✅ Toggle Action Menu
        const toggleActionMenu = (customerId) => {
            activeActionMenu.value = activeActionMenu.value === customerId ? null : customerId;
        };

        // ✅ View Customer Details
        const viewCustomer = (customerId) => {
            router.push(`/admin-customers/${customerId}`);
        };

        // ✅ Edit Customer (Redirect to Edit Form)
        const editCustomer = (customerId) => {
            router.push(`/admin-customers/edit/${customerId}`);
        };

        // ✅ Delete Customer
        const deleteCustomer = async (customerId) => {
            if (!confirm("Are you sure you want to delete this customer?")) return;
            try {
                await axiosClient.delete(`/admin/customers/${customerId}`);
                fetchCustomers();
            } catch (err) {
                console.error("Error deleting customer:", err);
            }
        };

        // ✅ Filter Customers (Search by Name, Email, Phone)
        const filteredCustomers = computed(() => {
            return customers.value.filter(customer =>
                customer.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                customer.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                customer.phone.toLowerCase().includes(searchQuery.value.toLowerCase())
            );
        });

        onMounted(fetchCustomers);

        return {
            customers, searchQuery, activeActionMenu, loading, error,
            fetchCustomers, toggleActionMenu, viewCustomer, editCustomer, deleteCustomer, filteredCustomers
        };
    },
};
