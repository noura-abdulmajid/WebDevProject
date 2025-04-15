import {ref, onMounted, nextTick} from "vue";
import Chart from "chart.js/auto";
import axiosClient from "@/services/axiosClient.js";
import apiConfig from "@/config/apiURL.js";

export default {
    setup() {
        const totalUsers = ref(0);
        const totalSales = ref(0);
        const totalOrders = ref(0);
        const totalSalesByYear = ref({});
        const yearlySalesSummary = ref([]);
        const yearlySalesData = ref([]);

        // Fetch Admin Dashboard Stats
        const fetchStats = async () => {
            try {
                const response = await axiosClient.get(apiConfig.admin.dashboardStats);
                const stats = response.data.stats;

                totalUsers.value = stats.total_users;
                totalSales.value = stats.total_sales;
                totalOrders.value = stats.total_orders;

                yearlySalesSummary.value = Object.keys(stats.yearly_sales).map(year => ({
                    year,
                    total_sales: stats.yearly_sales[year].total_sales,
                    total_buyers: stats.yearly_sales[year].total_buyers
                }));

                totalSalesByYear.value = Object.keys(stats.yearly_sales).reduce((acc, year) => {
                    acc[year] = stats.yearly_sales[year].total_sales;
                    return acc;
                }, {});

                const years = Object.keys(stats.monthly_sales);
                yearlySalesData.value = years.map(year => {
                    const monthlyData = stats.monthly_sales[year];
                    return {
                        year,
                        labels: Object.keys(monthlyData).map(month => `Month ${month}`),
                        data: Object.values(monthlyData).map(sale => sale.total_sales)
                    };
                });
            } catch (error) {
                console.error("Error fetching admin stats:", error.response?.data || error.message);
            }
        };

        const renderDonutChart = async () => {
            await nextTick();

            const years = Object.keys(totalSalesByYear.value);
            const sales = Object.values(totalSalesByYear.value);

            const ctx = document.getElementById("allYearsDonutChart").getContext("2d");

            new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: years,
                    datasets: [{
                        data: sales,
                        backgroundColor: years.map(() => getRandomColor()),
                        hoverBackgroundColor: years.map(() => getRandomColor())
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: "All Years Sales Distribution (%)"
                        },
                        tooltip: {
                            callbacks: {
                                label: function (tooltipItem) {
                                    const total = sales.reduce((a, b) => a + b, 0);
                                    const currentValue = sales[tooltipItem.dataIndex];
                                    const percentage = ((currentValue / total) * 100).toFixed(2);
                                    return `${years[tooltipItem.dataIndex]}: £${currentValue.toFixed(2)} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        };

        const getRandomColor = () => {
            const letters = "0123456789ABCDEF";
            let color = "#";
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        };

        const renderLineCharts = async () => {
            await nextTick();

            yearlySalesData.value.forEach((yearData, index) => {
                const canvasId = `salesLineChart-${index}`;
                const canvas = document.getElementById(canvasId);

                if (canvas) {
                    const ctx = canvas.getContext("2d");
                    new Chart(ctx, {
                        type: "line",
                        data: {
                            labels: yearData.labels,
                            datasets: [{
                                label: `Monthly Sales - ${yearData.year}`,
                                data: yearData.data,
                                borderColor: "blue",
                                fill: false
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: `Monthly Sales - ${yearData.year}`
                                },
                            }
                        }
                    });
                } else {
                    console.error(`Canvas with ID ${canvasId} not found`);
                }
            });
        };

        const renderPieCharts = async () => {
            await nextTick();

            yearlySalesData.value.forEach((yearData, index) => {
                const canvasId = `salesPieChart-${index}`;
                const canvas = document.getElementById(canvasId);

                if (canvas) {
                    const ctx = canvas.getContext("2d");
                    new Chart(ctx, {
                        type: "pie",
                        data: {
                            labels: yearData.labels,
                            datasets: [{
                                data: yearData.data,
                                backgroundColor: [
                                    "#FF6384", "#36A2EB", "#FFCE56", "#4CAF50",
                                    "#FF5722", "#9C27B0", "#2196F3", "#795548",
                                    "#CDDC39", "#FFC107", "#03A9F4", "#F44336"
                                ],
                            }],
                        },
                        options: {
                            plugins: {
                                title: {
                                    display: true,
                                    text: `Sales Distribution - ${yearData.year}`
                                }
                            }
                        }
                    });
                } else {
                    console.error(`Canvas with ID ${canvasId} not found`);
                }
            });
        };

        onMounted(async () => {
            await fetchStats();
            await nextTick();
            renderDonutChart();
            renderPieCharts();
            renderLineCharts();
        });

        return {
            totalUsers, totalSales, totalOrders, yearlySalesSummary,
            yearlySalesData
        };
    },
};