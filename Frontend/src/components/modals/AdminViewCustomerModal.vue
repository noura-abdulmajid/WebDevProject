<template>
  <div v-if="isVisible" class="modal-overlay">
    <div class="modal-content">

      <!-- Close Button -->
      <button class="close-btn" @click="$emit('close')">Close</button>

      <!-- Title -->
      <h3>Customer Details</h3>

      <!-- Customer Fields -->
      <p>
        <strong>C_ID:</strong> {{ customer.C_ID }}
      </p>
      <p>
        <strong>Name:</strong> {{ customer.first_name }} {{ customer.surname }}
      </p>
      <p>
        <strong>Email:</strong> {{ customer.email_address }}
      </p>
      <p>
        <strong>Phone:</strong> {{ customer.tel_no }}
      </p>
      <p>
        <strong>Shipping Address:</strong><br/>
        {{ customer.shipping_address }}
      </p>
      <p>
        <strong>Billing Address:</strong><br/>
        {{ customer.billing_address }}
      </p>

    </div>
  </div>
</template>

<script>
export default {
  name: 'AdminViewCustomerModal',
  props: {
    isVisible: {
      type: Boolean,
      default: false
    },
    customer: {
      type: Object,
      default: () => ({})
    }
  }
}
</script>

<style scoped>

.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  background-color: white;
  width: 400px;
  margin: 100px auto;
  padding: 1rem;
  border-radius: 6px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
  text-align: left;
}

.close-btn {
  float: right;
  cursor: pointer;
  background: none;
  border: none;
  font-size: 1.2rem;
}
</style>