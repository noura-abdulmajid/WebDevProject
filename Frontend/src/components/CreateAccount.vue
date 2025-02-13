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

        <div class="form-group">
          <label for="tel_no">Phone Number *</label>
          <input
              type="tel"
              id="tel_no"
              v-model="telNo"
              class="input-field"
              placeholder="Enter your phone number"
              required
          />
        </div>

        <div class="form-group">
          <label for="shipping_address">Shipping Address *</label>
          <input
              type="text"
              id="shipping_address"
              v-model="shippingAddress"
              class="input-field"
              placeholder="Enter your shipping address"
              required
          />
        </div>

        <div class="form-group">
          <label for="billing_address">Billing Address *</label>
          <input
              type="text"
              id="billing_address"
              v-model="billingAddress"
              class="input-field"
              placeholder="Enter your billing address"
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