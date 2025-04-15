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
          <router-link :to="{ name: 'admin-products' }">
            <img src="@/assets/image/icon/orders.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Product</span>
          </router-link>
        </li>
        <li>
          <router-link :to="{ name: 'admin-site-reviews' }">
            <img src="@/assets/image/icon/products.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Site Reviews</span>
          </router-link>
        </li>
        <li>
          <router-link :to="{ name: 'admin-orders' }">
            <img src="@/assets/image/icon/payment.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Order</span>
          </router-link>
        </li>
        <!--<li>
          <router-link :to="{ name: 'admin-shipping' }">
            <img src="@/assets/image/icon/shipping.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Shipping</span>
          </router-link>
        </li>
        <li>
          <router-link :to="{ name: 'admin-settings' }">
            <img src="@/assets/image/icon/settings.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Settings</span>
          </router-link>
        </li>
        <li v-if="isSuperAdmin">
          <router-link :to="{ name: 'admin-info' }">
            <img src="@/assets/image/icon/customer.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Admin Information</span>
          </router-link>
        </li>-->
        <li v-if="isSuperAdmin">
          <router-link :to="{ name: 'admin-users' }">
            <img src="@/assets/image/icon/customer.png" class="sidebar-icon" />
            <span v-if="!isSidebarCollapsed">Admin Users</span>
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

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();
const isSidebarCollapsed = ref(localStorage.getItem("sidebarCollapsed") === "true");

// Sidebar Toggle Function (Saves State)
const toggleSidebar = () => {
  isSidebarCollapsed.value = !isSidebarCollapsed.value;
  localStorage.setItem("sidebarCollapsed", isSidebarCollapsed.value);
};

// Dynamic Breadcrumbs
const breadcrumbs = computed(() => {
  const breadcrumbMappings = {
    "dashboard": "Dashboard",
    "customers": "Customers",
    "orders": "Orders",
    "payments": "Payments",
    "shipping": "Shipping",
    "products": "Products",
    "settings": "Settings"
  };

  const paths = route.path.split("/").filter((p) => p);
  let fullPath = "";
  return paths.map((segment) => {
    fullPath += `/${segment}`;
    return { name: breadcrumbMappings[segment] || segment.charAt(0).toUpperCase() + segment.slice(1), path: fullPath };
  });
});

const isSuperAdmin = computed(() => {
  return localStorage.getItem('admin_role') === 'super_admin';
});

// Check admin authentication
const checkAdminAuth = () => {
  const token = localStorage.getItem("jwt");
  const isAdmin = localStorage.getItem("isAdmin") === "true";
  
  if (!token || !isAdmin) {
    router.push('/admin/login');
    return false;
  }
  return true;
};

// Check auth on component mount
onMounted(() => {
  checkAdminAuth();
});
</script>

<style scoped src="../../styles/layouts/AdminLayoutStyle.css"></style>
