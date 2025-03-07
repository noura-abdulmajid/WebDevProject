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
                totalUsers.value = stats.total_users();
                totalSales.value = stats.total_sales;
                totalOrders.value = stats.total_orders;
            } catch (error) {
                console.error("Error fetching admin stats:", error.response?.data || error.message);
            }
        };

        onMounted(() => {
            fetchStats().then(() => {
                const ctx = document.getElementById("salesChart").getContext("2d");
                new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: months,
                        datasets: [{
                            label: "Sales",
                            data: salesData.value,
                            borderColor: "blue",
                            fill: false
                        }],
                    },
                });
            });
        });

        return { totalUsers, totalSales, totalOrders };
    },
};