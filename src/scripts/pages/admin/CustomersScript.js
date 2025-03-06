import {ref, computed, onMounted} from "vue";
import axiosClient from "@/services/axiosClient.js";
import {useRouter} from "vue-router";
import AdminAddCustomerModal from "@/components/modals/AdminAddCustomerModal.vue";
import AdminViewCustomerModal from '@/components/modals/AdminViewCustomerModal.vue';
import AdminDeleteCustomerModal from "@/components/modals/AdminDeleteCustomerModal.vue";
import apiConfig from "@/config/apiURL.js";

export default {
    components: {AdminAddCustomerModal, AdminViewCustomerModal, AdminDeleteCustomerModal},
    setup() {
        const customers = ref([]);
        const searchQuery = ref("");
        const selectedCustomers = ref([]);
        const customerToView = ref({});
        const showDeleteModal = ref(false);
        const customerToDelete = ref({});
        const selectAll = ref(false);
        const activeActionMenu = ref(null);
        const showModal = ref(false);
        const sortingCriteria = ref("name");
        const loading = ref(true);
        const error = ref(null);
        const viewModalVisible = ref(false);
        const router = useRouter();

        /**
         * Fetches the list of customers from the backend.
         * If an error occurs, it loads from localStorage.
         */
        const fetchCustomers = async () => {
            loading.value = true;
            error.value = null;
            try {
                const response = await axiosClient.get(apiConfig.admin.customers, {
                    params: {search: searchQuery.value},
                });

                customers.value = (response.data.customers || []).map((customer) => {

                    const orders = customer.orders_summary || {};

                    return {
                        id: customer.C_ID,
                        ...customer,
                        orders: {
                            ongoing: Number(orders.ongoing) || 0,
                            completed: Number(orders.completed) || 0,
                            total_spent: Number(orders.total_spent) || 0,
                            total_orders: (Number(orders.ongoing) || 0) + (Number(orders.completed) || 0),
                        },
                    };
                });

                console.log("Mapped Customers:", customers.value);
                localStorage.setItem("customers", JSON.stringify(customers.value));
            } catch (err) {
                error.value = "Failed to load customers. Displaying last available data.";
                console.error("Error fetching customers:", err);


                const localData = localStorage.getItem("customers");
                customers.value = localData ? JSON.parse(localData) : [];
            } finally {
                loading.value = false;
            }
        };


        /**
         * Get tooltips for customer order details.
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
                for (const id of selectedCustomers.value) {
                    await axiosClient.delete(apiConfig.admin.deleteUser(id));
                    selectedCustomers.value = [];
                    await fetchCustomers();
                }
            } catch (error) {
                console.error("Error deleting customers:", error);
            }
        };

        /**
         * Sorting Customers based on selected criteria.
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

        /**
         * Filter Customers based on search query.
         */
        const filteredCustomers = computed(() => {
            const query = searchQuery.value.toLowerCase();
            return sortedCustomers.value.filter((customer) => {
                const fullName = `${customer.first_name || ""} ${
                    customer.surname || ""
                }`.toLowerCase();
                return (
                    fullName.includes(query) ||
                    (customer.email_address?.toLowerCase() || "").includes(query) ||
                    (customer.tel_no?.toString() || "").includes(query)
                );
            });
        });

        /**
         * Select all customers at once.
         */
        const toggleSelectAll = () => {
            selectedCustomers.value = selectAll.value
                ? customers.value.map((customer) => customer.id)
                : [];
        };

        const viewCustomer = (customerId) => {
            const userData = customers.value.find(c => c.C_ID === customerId);
            if (userData) {
                customerToView.value = userData;
            }
            console.log("Found userData", userData);

            viewModalVisible.value = true;
            console.log("viewModalVisible", viewModalVisible.value);

        };

        const editCustomer = (customerId) => {
            router.push({name: 'admin-customer-profile', params: {id: customerId}});
        };

        const deleteCustomer = (customerId) => {
            const found = customers.value.find((c) => c.id === customerId);
            if (!found) return;

            customerToDelete.value = found;
            showDeleteModal.value = true;
        };

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


        onMounted(fetchCustomers);

        return {
            customers,
            searchQuery,
            selectedCustomers,
            viewModalVisible,
            customerToView,
            selectAll,
            activeActionMenu,
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
            getOrderTooltip

        };
    },
};