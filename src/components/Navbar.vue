<template>
  <nav class="navbar">
    <router-link to="/customer-dashboard">Dashboard</router-link>
    <button @click="handleLogout" class="logout-button">Logout</button>
  </nav>
</template>

<script>
import { logoutUser } from "../api/authService";
import { useRouter } from "vue-router";

export default {
  setup() {
    const router = useRouter();

    const handleLogout = async () => {
      const success = await logoutUser();
      if (success) router.push("/login"); // Redirect after logout
    };

    return { handleLogout };
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

.logout-button {
  background: red;
  color: white;
  padding: 8px 12px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}
</style>
