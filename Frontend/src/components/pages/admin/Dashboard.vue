<template>

  <h1>Sales Dashboard</h1>

  <main class="content">

    <!-- Show Loading Message When Fetching Data -->
    <p v-if="loading">Loading dashboard data...</p>
    <p v-if="error" class="error">{{ error }}</p>

    <!--Dashboard Stats Cards (Shows latest available data) -->
    <div class="dashboard-cards">
      <div class="card">
        <h3>Total Users</h3>
        <p>{{ totalUsers }}</p>
      </div>
      <div class="card">
        <h3>Total Orders</h3>
        <p>{{ totalOrders }}</p>
      </div>
      <div class="card">
        <h3>Total Sales</h3>
        <p>£{{ totalSales.toFixed(2) }}</p>
      </div>
    </div>

    <!-- Donut Chart for All Years -->
    <div class="donut-chart">
      <h2>All Years Sales Percentage</h2>
      <canvas id="allYearsDonutChart"></canvas>
    </div>

    <!-- Charts -->
    <div class="charts">
      <!-- Dynamic Pie Charts by Year -->
      <div v-for="(yearData, index) in yearlySalesData" :key="'pie-' + index" class="chart-container">
        <h4>Sales Pie Chart ({{ yearData.year }})</h4>
        <canvas :id="'salesPieChart-' + index"></canvas>
      </div>

      <!-- Dynamic Line Charts by Year -->
      <div v-for="(yearData, index) in yearlySalesData" :key="'line-' + index" class="chart-container">
        <h4>Sales Line Chart ({{ yearData.year }})</h4>
        <canvas :id="'salesLineChart-' + index"></canvas>
      </div>
    </div>
  </main>
</template>

<script>
import AdminDashboardScript from "../../../scripts/pages/admin/DashboardScript.js";

export default AdminDashboardScript;
</script>
<style scoped src="../../../styles/GlobalStyle.css"></style>
<style scoped src="../../../styles/pages/admin/DashboardStyle.css"></style>