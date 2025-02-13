<template>
  <div class="admin-dashboard">
    <!-- ✅ Sidebar -->
    <aside class="sidebar">
      <h2 class="nav-links">Admin Panel</h2>
      <ul class="nav-links">
        <li><router-link to="/admin-dashboard" class="active">Dashboard</router-link></li>
        <li><router-link to="/admin-customers">Customer</router-link></li>
        <li><router-link to="/admin-orders">Orders</router-link></li>
        <li><router-link to="/admin-products">Products</router-link></li>
        <li><router-link to="/admin-settings">Settings</router-link></li>
      </ul>
    </aside>

    <!-- ✅ Main Content -->
    <main class="content">
      <h1>Admin Dashboard</h1>

      <!-- ✅ Show Loading Message When Fetching Data -->
      <p v-if="loading">Loading dashboard data...</p>
      <p v-if="error" class="error">{{ error }}</p>

      <!-- ✅ Dashboard Stats Cards (Shows latest available data) -->
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

      <!-- ✅ Charts -->
      <div class="charts">
        <canvas id="salesChart"></canvas>
        <canvas id="ordersChart"></canvas>
      </div>
    </main>
  </div>
</template>

<script>
import AdminDashboardScript from "../scripts/AdminDashboardScript.js";
export default AdminDashboardScript;
</script>

<style scoped src="../styles/AdminDashboardStyle.css"></style>
