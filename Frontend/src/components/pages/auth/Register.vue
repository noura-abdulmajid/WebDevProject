<template>
  <div class="register-wrapper">
    <div class="register-container">
      <h1>Create Account</h1>
      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label for="first_name">First Name *</label>
          <input
              type="text"
              id="first_name"
              v-model="firstName"
              class="input-field"
              placeholder="Enter your first name"
              required
          />
        </div>

        <div class="form-group">
          <label for="surname">Last Name *</label>
          <input
              type="text"
              id="surname"
              v-model="surname"
              class="input-field"
              placeholder="Enter your last name"
              required
          />
        </div>

        <div class="form-group email-group">
          <label for="email">Email Address *</label>
          <div class="email-container">
            <input type="text" id="email" v-model="emailName" class="input-field" placeholder="Enter email name" />
            <select v-model="emailProvider">
              <option value="@gmail.com">@gmail.com</option>
              <option value="@yahoo.com">@yahoo.com</option>
              <option value="@outlook.com">@outlook.com</option>
            </select>
          </div>
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
import CreateAccountScript from "../../../scripts/pages/auth/RegisterScript.js";

export default CreateAccountScript;
</script>
<style scoped src="../../../styles/GlobalStyle.css"></style>
<style scoped src="../../../styles/pages/auth/RegisterStyle.css"></style>