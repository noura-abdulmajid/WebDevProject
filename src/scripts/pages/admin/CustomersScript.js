import { ref, computed, onMounted } from "vue";
import axiosClient from "@/services/axiosClient.js";
import { useRouter } from "vue-router";
import AdminAddCustomerModal from "@/components/modals/AdminAddCustomerModal.vue";

export default {
    components: { AdminAddCustomerModal },
    setup() {
        const customers = ref([]);
        const searchQuery = ref("");
        const selectedCustomers = ref([]);
        const selectAll = ref(false);
        const activeActionMenu = ref(null);
        const showModal = ref(false);
        const sortingCriteria = ref("name");
        const loading = ref(true);
        const error = ref(null);
        const router = useRouter();

        /**
         * Fetches the list of customers from the backend.
         * If an error occurs, it loads from localStorage.
         */
        const fetchCustomers = async () => {
            loading.value = true;
            error.value = null;
            try {
                const response = await axiosClient.get(`/admin/customers?search=${searchQuery.value}`);
                customers.value = response.data.customers || [];
                localStorage.setItem("customers", JSON.stringify(customers.value));
            } catch (err) {
                error.value = "Failed to load customers. Displaying last available data.";
                console.error("Error fetching customers:", err);
                customers.value = JSON.parse(localStorage.getItem("customers")) || [];
            } finally {
                loading.value = false;
            }
        };

        /**
         * Bulk delete selected customers.
         * Ensures a confirmation prompt before deleting.
         */
        const bulkDeleteCustomers = async () => {
            if (selectedCustomers.value.length === 0) return alert("No customers selected.");
            if (!confirm("Are you sure you want to delete selected customers?")) return;
            try {
                await axiosClient.delete("/admin/customers/bulk-delete", { data: { customers: selectedCustomers.value } });
                alert("Selected customers deleted.");
                selectedCustomers.value = [];
                fetchCustomers();
            } catch (error) {
                console.error("Error deleting customers:", error);
            }
        };

        /**
         * Toggle the action menu for each customer.
         */
        const toggleActionMenu = (customerId) => {
            activeActionMenu.value = activeActionMenu.value === customerId ? null : customerId;
        };

        /**
         * Navigate to the customer details page.
         */
        const viewCustomer = (customerId) => {
            if (!customerId) return;
            router.push(`/admin/customers/${customerId}`);
        };

        /**
         * Navigate to the customer edit page.
         */
        const editCustomer = (customerId) => {
            if (!customerId) return;
            router.push(`/admin/customers/edit/${customerId}`);
        };

        /**
         * Deletes a single customer after confirmation.
         */
        const deleteCustomer = async (customerId) => {
            if (!customerId) return;
            if (!confirm("Are you sure you want to delete this customer?")) return;
            try {
                await axiosClient.delete(`/admin/customers/${customerId}`);
                fetchCustomers();
            } catch (err) {
                console.error("Error deleting customer:", err);
                alert("Unable to delete customer. Please try again.");
            }
        };

        /**
         * Sorting Customers based on selected criteria.
         */
        const sortedCustomers = computed(() => {
            return [...customers.value].sort((a, b) => {
                if (sortingCriteria.value === "name") return a.name.localeCompare(b.name);
                if (sortingCriteria.value === "amount_spent") return b.amount_spent - a.amount_spent;
                if (sortingCriteria.value === "signupDate") return new Date(b.signupDate) - new Date(a.signupDate);
                return 0;
            });
        });

        /**
         * Filter Customers based on search query.
         */
        const filteredCustomers = computed(() => {
            return sortedCustomers.value.filter(customer =>
                (customer.name?.toLowerCase() || "").includes(searchQuery.value.toLowerCase()) ||
                (customer.email?.toLowerCase() || "").includes(searchQuery.value.toLowerCase()) ||
                (customer.phone?.toLowerCase() || "").includes(searchQuery.value.toLowerCase())
            );
        });

        /**
         * Select all customers at once.
         */
        const toggleSelectAll = () => {
            selectAll.value = !selectAll.value;
            selectedCustomers.value = selectAll.value ? customers.value.map(customer => customer.id) : [];
        };

        onMounted(fetchCustomers);

        return {
            customers, searchQuery, selectedCustomers, selectAll, activeActionMenu, showModal, sortingCriteria,
            loading, error, fetchCustomers, bulkDeleteCustomers, toggleActionMenu, viewCustomer,
            editCustomer, deleteCustomer, filteredCustomers, toggleSelectAll
        };
    },
};
