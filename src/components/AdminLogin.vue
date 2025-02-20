<template>
  <div class="admin-login-wrapper">
    <div class="admin-login-container">
      <h1>Admin Login</h1>
      <form @submit.prevent="handleAdminLogin">
        <div class="form-group">
          <label for="email">Email *</label>
          <input
              type="email"
              id="email"
              v-model="email"
              class="input-field"
              placeholder="Enter your admin email"
              required
          />
        </div>

        <div class="form-group">
          <label for="password">Password *</label>
          <input
              type="password"
              id="password"
              v-model="password"
              class="input-field"
              placeholder="Enter your admin password"
              required
          />
        </div>

        <button type="submit" class="login-button" :disabled="loading">
          {{ loading ? "Logging in..." : "Login" }}
        </button>

        <p v-if="error" class="error-message">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script>
import AdminLoginScript from "../scripts/AdminLoginScript.js";
export default AdminLoginScript;
</script>

<style scoped src="../styles/AdminLoginStyle.css"></style>
<style scoped src="../styles/GlobalStyle.css"></style>
