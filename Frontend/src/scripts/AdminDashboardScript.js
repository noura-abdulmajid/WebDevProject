import { ref, onMounted } from "vue";
import Chart from "chart.js/auto";
import axiosClient from "../api/axiosClient";

export default {
    setup() {
        const totalUsers = ref(parseInt(localStorage.getItem("totalUsers")) || 0);
        const totalOrders = ref(parseInt(localStorage.getItem("totalOrders")) || 0);
        const totalSales = ref(parseFloat(localStorage.getItem("totalSales")) || 0);
        const loading = ref(true);
        const error = ref(null);

        // ✅ Default empty chart data
        const salesData = ref(new Array(12).fill(0));  // Ensure all 12 months are displayed
        const ordersData = ref(new Array(12).fill(0));

        // ✅ Fetch Dashboard Stats from Backend
        const fetchStats = async () => {
            try {
                const response = await axiosClient.get("/admin/stats");
                totalUsers.value = response.data.totalUsers;
                totalOrders.value = response.data.totalOrders;
                totalSales.value = response.data.totalSales;

                // ✅ Save latest available data in case API fails later
                localStorage.setItem("totalUsers", response.data.totalUsers);
                localStorage.setItem("totalOrders", response.data.totalOrders);
                localStorage.setItem("totalSales", response.data.totalSales);
            } catch (err) {
                error.value = "⚠ Failed to load dashboard data. Displaying last available data.";
                console.error("Error fetching dashboard stats:", err);
            } finally {
                loading.value = false;
            }
        };

        // ✅ Fetch Sales Trend Data
        const fetchSalesTrend = async () => {
            try {
                const response = await axiosClient.get("/admin/sales-trend");
                salesData.value = response.data.sales || new Array(12).fill(0);
            } catch (error) {
                console.error("Failed to fetch sales trend:", error);
                salesData.value = new Array(12).fill(0);
            }
        };

        // ✅ Fetch Orders Trend Data
        const fetchOrdersTrend = async () => {
            try {
                const response = await axiosClient.get("/admin/orders-trend");
                ordersData.value = response.data.orders || new Array(12).fill(0);
            } catch (error) {
                console.error("Failed to fetch orders trend:", error);
                ordersData.value = new Array(12).fill(0);
            }
        };

        // ✅ Initialize Charts
        const createCharts = () => {
            const salesCtx = document.getElementById("salesChart").getContext("2d");
            new Chart(salesCtx, {
                type: "line",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{ label: "Sales", data: salesData.value, borderColor: "blue", fill: false }],
                },
            });

            const ordersCtx = document.getElementById("ordersChart").getContext("2d");
            new Chart(ordersCtx, {
                type: "bar",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{ label: "Orders", data: ordersData.value, backgroundColor: "green" }],
                },
            });
        };

        // ✅ Load Data & Charts on Component Mount
        onMounted(async () => {
            await fetchStats();
            await fetchSalesTrend();
            await fetchOrdersTrend();
            createCharts();
        });

        return { totalUsers, totalOrders, totalSales, salesData, ordersData, loading, error };
    },
};
