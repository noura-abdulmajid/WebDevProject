<template>
  <nav class="navbar">
    <div class="nav-links">
      <router-link v-if="role === 'customer'" to="/customer-dashboard">Customer Dashboard</router-link>
      <router-link v-if="role === 'admin'" to="/admin-dashboard">Admin Dashboard</router-link>
      <router-link v-if="!role" to="/admin-login">Admin Login</router-link>
    </div>

    <button v-if="role" @click="handleLogout" class="logout-button">Logout</button>
  </nav>
</template>

<script>
import { logoutUser } from "@/services/authService";
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";

export default {
  setup() {
    const router = useRouter();
    const role = ref(null);

    // Fetch user role from localStorage when the component is mounted
    onMounted(() => {
      role.value = localStorage.getItem("role");
    });

    const handleLogout = async () => {
      const success = await logoutUser();
      if (success) {
        localStorage.removeItem("role");
        router.push(role.value === "admin" ? "/admin-login" : "/login");
      }
    };

    return { handleLogout, role };
  },
};
</script>

<style scoped>
.navbar {
  display: flex;
  justify-content: space-between;
  padding: 10px;
  background-color: #333;
  color: white;
}

.nav-links {
  display: flex;
  gap: 15px;
}

.logout-button {
  background: red;
  color: white;
  padding: 8px 12px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}
</style>
