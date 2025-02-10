<template>
  <div class="admin-dashboard">
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
      <h2 class="logo">Admin Panel</h2>
      <ul class="nav-links">
        <li><router-link to="/admin-dashboard">Dashboard</router-link></li>
        <li><router-link to="/admin-users">Users</router-link></li>
        <li><router-link to="/admin-products">Products</router-link></li>
        <li><router-link to="/admin-orders">Orders</router-link></li>
        <li><router-link to="/admin-settings">Settings</router-link></li>
      </ul>
    </aside>

    <!-- Main Content -->
    <main class="content">
      <h1>Dashboard Overview</h1>

      <div class="stats-grid">
        <div class="stat-card">
          <h3>Total Users</h3>
          <p>{{ totalUsers }}</p>
        </div>
        <div class="stat-card">
          <h3>Total Sales</h3>
          <p>${{ totalSales }}</p>
        </div>
        <div class="stat-card">
          <h3>Total Orders</h3>
          <p>{{ totalOrders }}</p>
        </div>
      </div>

      <!-- Chart Placeholder -->
      <div class="chart-container">
        <h3>Performance Overview</h3>
        <canvas id="salesChart"></canvas>
      </div>
    </main>
  </div>
</template>

<script>
import AdminDashboardScript from "../scripts/AdminDashboardScript.js";
export default AdminDashboardScript;
</script>

<style scoped src="../styles/AdminDashboardStyle.css"></style>
