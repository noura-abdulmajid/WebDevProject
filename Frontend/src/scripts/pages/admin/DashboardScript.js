import { ref, onMounted } from "vue";
import Chart from "chart.js/auto";
import axiosClient from "@/services/axiosClient.js";
import apiConfig from "@/config/apiURL.js";

export default {
    setup() {
        const totalUsers = ref(0);
        const totalSales = ref(0);
        const totalOrders = ref(0);

        // Fetch Admin Dashboard Stats
        const fetchStats = async () => {
            try {
                const response = await axiosClient.get(apiConfig.admin.dashboardStats);
                const stats = response.data.stats;
                totalUsers.value = stats.total_users;
                totalSales.value = stats.total_sales;
                totalOrders.value = stats.total_orders;
            } catch (error) {
                console.error("Error fetching admin stats:", error.response?.data || error.message);
            }
        };

        onMounted(() => {
            fetchStats();

            // Initialize Chart.js for performance visualization
            const ctx = document.getElementById("salesChart").getContext("2d");
            new Chart(ctx, {
                type: "line",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May"],
                    datasets: [{
                        label: "Sales",
                        data: [50, 60, 70, 80, 100],
                        borderColor: "blue",
                        fill: false
                    }],
                },
            });
        });

        return { totalUsers, totalSales, totalOrders };
    },
};