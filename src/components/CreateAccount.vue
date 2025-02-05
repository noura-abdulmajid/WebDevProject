<template>
  <div class="register-wrapper">
    <div class="register-container">
      <h1>Create Account</h1>
      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label for="name">Full Name *</label>
          <input
              type="text"
              id="name"
              v-model="name"
              class="input-field"
              placeholder="Enter your full name"
              required
          />
        </div>

        <div class="form-group">
          <label for="email">Email Address *</label>
          <input
              type="email"
              id="email"
              v-model="email"
              class="input-field"
              placeholder="Enter your email"
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
              placeholder="Enter your password"
              required
          />
        </div>

        <div class="form-group">
          <label for="confirm-password">Confirm Password *</label>
          <input
              type="password"
              id="confirm-password"
              v-model="confirmPassword"
              class="input-field"
              placeholder="Re-enter your password"
              required
          />
        </div>

        <button type="submit" class="register-button" :disabled="loading">
          {{ loading ? "Creating Account..." : "Create Account" }}
        </button>

        <p v-if="error" class="error-message">{{ error }}</p>
        <p v-if="success" class="success-message">{{ success }}</p>
      </form>

      <div class="register-links">
        <router-link to="/login">Already have an account? Login</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import CreateAccountScript from "../scripts/CreateAccountScript.js";
export default CreateAccountScript;
</script>

<style scoped src="../styles/CreateAccountStyle.css"></style>
