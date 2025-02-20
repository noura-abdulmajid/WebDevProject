<template>
  <div class="reset-password-wrapper">
    <div class="reset-password-container">
      <h1>Reset Your Password</h1>
      <form @submit.prevent="handlePasswordReset">
        <div class="form-group">
          <label for="password">New Password *</label>
          <input
              type="password"
              id="password"
              v-model="password"
              class="input-field"
              placeholder="Enter new password"
              required
          />
        </div>

        <div class="form-group">
          <label for="confirm-password">Confirm New Password *</label>
          <input
              type="password"
              id="confirm-password"
              v-model="confirmPassword"
              class="input-field"
              placeholder="Re-enter new password"
              required
          />
        </div>

        <button type="submit" class="reset-button" :disabled="loading">
          {{ loading ? "Resetting Password..." : "Reset Password" }}
        </button>

        <p v-if="message" class="success-message">{{ message }}</p>
        <p v-if="error" class="error-message">{{ error }}</p>
      </form>

      <div class="reset-links">
        <router-link to="/login">Back to Login</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import ResetPasswordScript from "../scripts/ResetPasswordScript.js";
export default ResetPasswordScript;
</script>
<style scoped src="../styles/GlobalStyle.css"></style>
<style scoped src="../styles/ResetPasswordStyle.css"></style>
