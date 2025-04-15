<template>
    <div class="edit-customer">
      <h1>Edit Customer</h1>

      <div v-if="loading">Loading...</div>
      <div v-else-if="error">
        <p class="error-message">{{ error }}</p>
      </div>
      <div v-else>
        <form @submit.prevent="updateCustomer">
          <div class="form-group">
            <label>Full Name *</label>
            <input type="text" v-model="customer.name" required />
          </div>

          <div class="form-group">
            <label>Email *</label>
            <input type="email" v-model="customer.email" required />
          </div>

          <div class="form-group">
            <label>Phone *</label>
            <input type="text" v-model="customer.phone" required />
          </div>

          <div class="form-group">
            <label>Address</label>
            <input type="text" v-model="customer.address" />
          </div>

          <button type="submit" class="save-button">💾 Save Changes</button>
          <button type="button" @click="goBack" class="cancel-button">❌ Cancel</button>
        </form>
      </div>
    </div>
</template>

<script src="../../../scripts/pages/admin/EditCustomerProfileScript.js"></script>
<style scoped src="../../../styles/pages/admin/EditCustomerProfileStyle.css"></style>
