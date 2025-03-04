<template>
  <div class="admin-dashboard">
    <!-- Sidebar -->
    <aside :class="['sidebar', { collapsed: isSidebarCollapsed }]">
      <h2 v-if="!isSidebarCollapsed" class="nav-links">Admin Panel</h2>
      <button @click="toggleSidebar" class="toggle-btn">☰</button>
      <ul class="nav-links">
        <li>
          <router-link :to="{ name: 'admin-dashboard' }">
          <img src="@/assets/image/icon/dashboard.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Dashboard</span>
          </router-link>
        </li>
        <li>
          <router-link :to="{ name: 'admin-customers' }">
            <img src="@/assets/image/icon/customer.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Customers</span>
          </router-link>
        </li>
        <li>
          <router-link :to="{ name: 'admin-orders' }">
            <img src="@/assets/image/icon/orders.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Orders</span>
          </router-link>
        </li>
        <li>
          <router-link to="/admin-payments">
            <img src="@/assets/image/icon/payment.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Payments</span>
          </router-link>
        </li>
        <li>
          <router-link to="/admin-shipping">
            <img src="@/assets/image/icon/shipping.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Shipping</span>
          </router-link>
        </li>
        <li>
          <router-link :to="{ name: 'admin-products' }">
            <img src="@/assets/image/icon/products.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Products</span>
          </router-link>
        </li>
        <li>
          <router-link :to="{ name: 'admin-settings' }">
            <img src="@/assets/image/icon/settings.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Settings</span>
          </router-link>
        </li>
      </ul>
    </aside>

    <!-- Main Content -->
    <main :class="['content', { 'full-width': isSidebarCollapsed }]">
      <div class="breadcrumb">
        <router-link to="/admin/dashboard">Dashboard</router-link>
        <span v-for="(crumb, index) in breadcrumbs" :key="index">
          > <router-link :to="crumb.path">{{ crumb.name }}</router-link>
        </span>
      </div>

      <!-- Renders admin pages inside layout -->
      <router-view></router-view>

    </main>
  </div>
</template>

<script src="../../scripts/layouts/AdminLayoutScript.js"></script>
<style scoped src="../../styles/layouts/AdminLayoutStyle.css"></style>
