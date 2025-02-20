<template>
  <div class="login-wrapper">
    <div class="login-container">
      <h1>Login</h1>
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
import LoginScript from "@/scripts/LoginScript.js";

export default LoginScript;
</script>
<style src="../styles/GlobalStyle.css"></style>
<style src="../styles/LoginStyle.css"></style>
