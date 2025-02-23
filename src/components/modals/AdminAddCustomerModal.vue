<template>
  <div class="modal-overlay" v-if="isVisible">
    <div class="modal">
      <button class="close-btn" @click="closeModal">✖</button> <!-- ✅ Close button added -->
      <h2>Create Account</h2>

      <!-- First Name -->
      <div class="form-group">
        <label>First Name *</label>
        <input type="text" v-model="newCustomer.firstName" class="input-field" placeholder="Enter first name" required />
      </div>

      <!-- Last Name -->
      <div class="form-group">
        <label>Last Name *</label>
        <input type="text" v-model="newCustomer.surname" class="input-field" placeholder="Enter last name" required />
      </div>

      <!-- Email -->
      <div class="form-group email-group">
        <label>Email Address *</label>
        <div class="email-container">
          <input type="text" v-model="newCustomer.emailName" class="input-field" placeholder="Enter email name" required />
          <select v-model="newCustomer.emailProvider">
            <option value="@gmail.com">@gmail.com</option>
            <option value="@hotmail.com">@hotmail.com</option>
            <option value="@outlook.com">@outlook.com</option>
          </select>
        </div>
      </div>

      <!-- Phone -->
      <div class="form-group">
        <label>Phone *</label>
        <input type="text" v-model="newCustomer.phone" class="input-field" placeholder="Enter phone number" required />
      </div>

      <!-- Password -->
      <div class="form-group">
        <label>Password *</label>
        <input type="password" v-model="newCustomer.password" class="input-field" placeholder="Enter password" required />
      </div>

      <!-- Confirm Password -->
      <div class="form-group">
        <label>Confirm Password *</label>
        <input type="password" v-model="newCustomer.confirmPassword" class="input-field" placeholder="Re-enter password" required />
      </div>

      <!-- Buttons -->
      <button @click="submitCustomer" class="register-button">Create Account</button>
      <button @click="closeModal" class="cancel-button">Cancel</button>
    </div>
  </div>
</template>

<script src="../../scripts/modals/AdminAddCustomerModalScript.js"></script>
<style scoped src="../../styles/modals/AdminAddCustomerModalStyle.css"></style>
