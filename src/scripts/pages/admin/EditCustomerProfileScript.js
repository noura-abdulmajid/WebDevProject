import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axiosClient from "@/services/axiosClient.js";

export default {
    setup() {
        const customer = ref(null);
        const loading = ref(true);
        const error = ref(null);
        const route = useRoute();
        const router = useRouter();

        /**
         * ✅ Fetch customer details from API.
         */
        const fetchCustomer = async () => {
            try {
                const response = await axiosClient.get(`/admin-customers/${route.params.id}`);
                customer.value = response.data.customer;
                if (!customer.value) throw new Error("Customer not found.");
            } catch (err) {
                error.value = "⚠ Unable to retrieve customer data.";
                console.error("Error fetching customer:", err);
            } finally {
                loading.value = false;
            }
        };

        /**
         * ✅ Update customer details via API.
         */
        const updateCustomer = async () => {
            try {
                await axiosClient.put(`/admin-customers/${route.params.id}`, {
                    name: customer.value.name,
                    email: customer.value.email,
                    phone: customer.value.phone,
                    address: customer.value.address
                });

                alert("Customer details updated successfully!");
                goBack();
            } catch (err) {
                console.error("Error updating customer:", err);
                alert("Failed to update customer details.");
            }
        };

        /**
         * ✅ Navigate back to the customer profile.
         */
        const goBack = () => {
            router.push(`/admin-customers/view/${route.params.id}`);
        };

        onMounted(fetchCustomer);

        return { customer, loading, error, updateCustomer, goBack };
    },
};
