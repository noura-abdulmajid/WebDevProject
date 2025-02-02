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
        <a href="/forgot-password">Forgot password?</a>
      </div>
    </div>
  </div>
</template>
