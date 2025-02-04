<template>
  <div class="login-wrapper">
    <div class="login-container">
      <h1>Login Now</h1>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="email">Username *</label>
          <input
              type="email"
              id="email"
              v-model="email"
              class="input-field"
              placeholder="Enter your Username"
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
              placeholder="Enter your Password"
              required
          />
        </div>

        <div class="form-group remember-me">
          <input type="checkbox" id="rememberMe" v-model="rememberMe" />
          <label for="rememberMe">Remember me</label>
        </div>

        <button type="submit" class="login-button" :disabled="loading">
          {{ loading ? "Logging in..." : "Login" }}
        </button>
      </form>

      <div class="login-links">
        <router-link to="/register">Don’t have an account?</router-link>
        <router-link to="/forgot-password">Forgot password?</router-link>
      </div>

      <div v-if="error" class="error-message">{{ error }}</div>
    </div>
  </div>
</template>

<script>
import { login } from "@/api/users";

export default {
  data() {
    return {
      email: "",
      password: "",
      rememberMe: false,
      loading: false,
      error: null,
    };
  },
  methods: {
    async handleLogin() {
      this.loading = true;
      this.error = null;

      try {
        const data = await login(this.email, this.password);

        if (data.user.role === "admin") {
          this.$router.push("/admin-dashboard");
        } else {
          this.$router.push("/customer-dashboard");
        }
      } catch (error) {
        this.error = error.response?.data?.message || "Login failed!";
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>