<template>
  <div class="forgot-password-wrapper">
    <div class="forgot-password-container">
      <h1>Reset Your Password</h1>
      <form @submit.prevent="handleForgotPassword">
        <div class="form-group">
          <label for="email">Enter Your Email Address *</label>
          <input
              type="email"
              id="email"
              v-model="email"
              class="input-field"
              placeholder="Enter your email"
              required
          />
        </div>

        <!-- Google reCAPTCHA -->
        <div class="g-recaptcha" data-sitekey="your-site-key"></div>

        <button type="submit" class="reset-button" :disabled="loading">
          {{ loading ? "Sending Reset Link..." : "Send Reset Link" }}
        </button>

        <p v-if="message" class="success-message">{{ message }}</p>
        <p v-if="error" class="error-message">{{ error }}</p>
      </form>

      <div class="forgot-links">
        <router-link to="/login">Back to Login</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import ForgotPasswordScript from "../scripts/ForgotPasswordScript.js";
export default ForgotPasswordScript;
</script>

<style scoped src="../styles/ForgotPasswordStyle.css"></style>
