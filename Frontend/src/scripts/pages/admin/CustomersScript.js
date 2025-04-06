import {ref, computed, onMounted} from "vue";
import axiosClient from "@/services/axiosClient.js";
import {useRouter} from "vue-router";
import AdminAddCustomerModal from "@/components/modals/AdminAddCustomerModal.vue";
import AdminViewCustomerModal from "@/components/modals/AdminViewCustomerModal.vue";
import AdminDeleteCustomerModal from "@/components/modals/AdminDeleteCustomerModal.vue";
import apiConfig from "@/config/apiURL.js";

export default {
    components: {
        AdminAddCustomerModal,
        AdminViewCustomerModal,
        AdminDeleteCustomerModal,
    },

    setup() {
        // Reactive state variables
        const customers = ref([]);
        const searchQuery = ref("");
        const selectedCustomers = ref([]);
        const customerToView = ref({});
        const showDeleteModal = ref(false);
        const customerToDelete = ref({});
        const selectAll = ref(false);
        const showModal = ref(false);
        const sortingCriteria = ref("name");
        const loading = ref(true);
        const error = ref(null);
        const viewModalVisible = ref(false);
        const router = useRouter();
        const currentPage = ref(1);
        const customersPerPage = 20;
        const filters = ref({
            minOrders: 0,
            minSpent: 0,
        });

        /**
         * Fetches the list of customers from the backend.
         * If an error occurs, it loads from localStorage.
         */
        const fetchCustomers = async () => {
            loading.value = true;
            error.value = null;
            try {
                console.log('Fetching customers from:', apiConfig.admin.customers);
                const token = localStorage.getItem('admin_token');
                if (!token) {
                    throw new Error('No admin token found');
                }
                
                const response = await axiosClient.get(apiConfig.admin.customers, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    params: {search: searchQuery.value},
                });
                
                console.log('Customers response:', response.data);
                
                if (response.data && response.data.customers) {
                    customers.value = response.data.customers.map((customer) => {
                        const orders = customer.orders_summary || {};
                        return {
                            id: customer.C_ID,
                            first_name: customer.first_name,
                            surname: customer.surname,
                            email_address: customer.email_address,
                            tel_no: customer.tel_no,
                            shipping_address: customer.shipping_address,
                            billing_address: customer.billing_address,
                            orders: {
                                ongoing: Number(orders.ongoing) || 0,
                                completed: Number(orders.completed) || 0,
                                total_spent: Number(orders.total_spent) || 0,
                                total_orders:
                                    (Number(orders.ongoing) || 0) + (Number(orders.completed) || 0),
                            },
                        };
                    });
                    // Save fetched customers to localStorage
                    localStorage.setItem("customers", JSON.stringify(customers.value));
                } else {
                    throw new Error('Invalid response format');
                }
            } catch (err) {
                console.error('Error fetching customers:', err);
                if (err.response) {
                    console.error('Error response:', err.response.data);
                    if (err.response.status === 401) {
                        error.value = 'Your session has expired. Please login again.';
                        router.push('/admin-login');
                    } else if (err.response.status === 403) {
                        error.value = 'You do not have permission to access this page.';
                    } else {
                        error.value = `Failed to fetch customers: ${err.response.data.message || 'Unknown error'}`;
                    }
                } else if (err.request) {
                    console.error('Error request:', err.request);
                    error.value = 'Unable to connect to server. Please check your network connection.';
                } else {
                    error.value = `Error occurred while fetching customers: ${err.message}`;
                }
                
                // Try to load from localStorage as fallback
                const localData = localStorage.getItem("customers");
                if (localData) {
                    customers.value = JSON.parse(localData);
                    error.value = 'Showing last available data';
                } else {
                    customers.value = [];
                }
            } finally {
                loading.value = false;
            }
        };

        // Computed property for total pages based on filtered customers
        const totalPages = computed(() =>
            Math.ceil(filteredCustomers.value.length / customersPerPage)
        );

        // Computed property for customers to be displayed on the current page
        const paginatedCustomers = computed(() => {
            const startIndex = (currentPage.value - 1) * customersPerPage;
            return filteredCustomers.value.slice(
                startIndex,
                startIndex + customersPerPage
            );
        });

        /**
         * Changes the current page.
         * @param {number} page - The new page number.
         */
        const changePage = (page) => {
            if (page > 0 && page <= totalPages.value) {
                currentPage.value = page;
            }
        };

        /**
         * Get tooltips for customer order details.
         * @param {object} orders - The orders object.
         * @returns {string} Tooltip string with ongoing and completed orders.
         */
        const getOrderTooltip = (orders) => {
            if (!orders) {
                return "No order data available.";
            }
            const ongoing = orders.ongoing || 0;
            const completed = orders.completed || 0;
            return `Ongoing: ${ongoing}, Completed: ${completed}`;
        };

        /**
         * Bulk delete selected customers.
         */
        const bulkDeleteCustomers = async () => {
            if (selectedCustomers.value.length === 0)
                return alert("No customers selected.");
            if (!confirm("Are you sure you want to delete selected customers?"))
                return;
            try {
                // Delete each selected customer
                for (const id of selectedCustomers.value) {
                    await axiosClient.delete(apiConfig.admin.deleteUser(id));
                }
                selectedCustomers.value = [];
                await fetchCustomers();
            } catch (err) {
                console.error("Error deleting customers:", err);
            }
        };

        /**
         * Sorts customers based on the selected criteria.
         */
        const sortedCustomers = computed(() => {
            return [...customers.value].sort((a, b) => {
                if (sortingCriteria.value === "name") {
                    const nameA = `${a.first_name || ""} ${a.surname || ""}`.toLowerCase();
                    const nameB = `${b.first_name || ""} ${b.surname || ""}`.toLowerCase();
                    return nameA.localeCompare(nameB);
                }
                return 0;
            });
        });

        // Filter options for checkbox-based filtering
        const filterOptions = ref({
            orders: [
                {label: "All Orders", value: 0, checked: true},
                {label: "5+ Orders", value: 5, checked: false},
                {label: "10+ Orders", value: 10, checked: false},
            ],
            amountSpent: [
                {label: "All Spent", value: 0, checked: true},
                {label: "£100+", value: 100, checked: false},
                {label: "£500+", value: 500, checked: false},
            ],
        });

        /**
         * Apply filters based on filterOptions.
         * This method updates the filters object using the active filter criteria.
         */
        const applyFilters = () => {
            // Update filters.minOrders based on checked orders options
            const selectedOrders = filterOptions.value.orders
                .filter((opt) => opt.checked)
                .map((opt) => opt.value);
            filters.value.minOrders = selectedOrders.length ? Math.max(...selectedOrders) : 0;

            // Update filters.minSpent based on checked amountSpent options
            const selectedSpent = filterOptions.value.amountSpent
                .filter((opt) => opt.checked)
                .map((opt) => opt.value);
            filters.value.minSpent = selectedSpent.length ? Math.max(...selectedSpent) : 0;
        };

        /**
         * Filter customers based on search query and filter criteria.
         */
        const filteredCustomers = computed(() => {
            const query = searchQuery.value.toLowerCase();
            return sortedCustomers.value.filter((customer) => {
                const fullName = `${customer.first_name || ""} ${customer.surname || ""}`.toLowerCase();
                return (
                    (fullName.includes(query) ||
                        (customer.email_address?.toLowerCase() || "").includes(query) ||
                        (customer.tel_no?.toString() || "").includes(query)) &&
                    customer.orders.total_orders >= filters.value.minOrders &&
                    customer.orders.total_spent >= filters.value.minSpent
                );
            });
        });


        /**
         * Select or deselect all customers.
         */
        const toggleSelectAll = () => {
            selectedCustomers.value = selectAll.value
                ? customers.value.map((customer) => customer.id)
                : [];
        };

        /**
         * Set the customer to view and open the view modal.
         * @param {number} customerId - The ID of the customer to view.
         */
        const viewCustomer = (customerId) => {
            const userData = customers.value.find((c) => c.C_ID === customerId);
            if (userData) {
                customerToView.value = userData;
            }
            viewModalVisible.value = true;
        };

        /**
         * Navigate to the customer edit page.
         * @param {number} customerId - The ID of the customer to edit.
         */
        const editCustomer = (customerId) => {
            router.push({name: "admin-customer-profile", params: {id: customerId}});
        };

        /**
         * Open the delete modal for the selected customer.
         * @param {number} customerId - The ID of the customer to delete.
         */
        const deleteCustomer = (customerId) => {
            const found = customers.value.find((c) => c.id === customerId);
            if (!found) return;
            customerToDelete.value = found;
            showDeleteModal.value = true;
        };

        /**
         * Confirm deletion of a customer.
         * @param {object} customer - The customer object to delete.
         */
        const onConfirmDelete = async (customer) => {
            showDeleteModal.value = false;
            try {
                await axiosClient.delete(apiConfig.admin.deleteUser(customer.id));
                alert("Deleted successfully.");
                await fetchCustomers();
            } catch (err) {
                console.error("Error deleting customer", err);
            }
        };

        /**
         * Clear all filters by resetting filterOptions to default values.
         */
        const clearFilters = () => {
            filterOptions.value.orders.forEach((opt, index) => {
                opt.checked = index === 0;
            });
            filterOptions.value.amountSpent.forEach((opt, index) => {
                opt.checked = index === 0;
            });
            // After clearing, apply filters to update filters object
            applyFilters();
        };

        onMounted(fetchCustomers);

        return {
            customers, searchQuery,
            selectedCustomers,
            viewModalVisible,
            customerToView,
            selectAll,
            showModal,
            sortingCriteria,
            loading,
            error,
            fetchCustomers,
            bulkDeleteCustomers,
            filteredCustomers,
            toggleSelectAll,
            viewCustomer,
            editCustomer,
            deleteCustomer,
            showDeleteModal,
            customerToDelete,
            onConfirmDelete,
            getOrderTooltip,
            currentPage,
            customersPerPage,
            totalPages,
            paginatedCustomers,
            changePage,
            filters,
            filterOptions,
            clearFilters,
            applyFilters
        };
    },
};
