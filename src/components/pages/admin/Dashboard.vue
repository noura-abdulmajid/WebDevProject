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

    <!-- Charts -->
    <div class="charts">
      <canvas id="salesChart"></canvas>
      <canvas id="ordersChart"></canvas>
    </div>
  </main>
</template>

<script>
import AdminDashboardScript from "../../../scripts/pages/admin/DashboardScript.js";
export default AdminDashboardScript;
</script>
<style scoped src="../../../styles/GlobalStyle.css"></style>
<style scoped src="../../../styles/pages/admin/DashboardStyle.css"></style>