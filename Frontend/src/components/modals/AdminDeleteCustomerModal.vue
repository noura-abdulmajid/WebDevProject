<template>
  <!-- This modal is only displayed when isVisible is true -->
  <div v-if="isVisible" class="modal-overlay">
    <div class="modal-content">

      <!-- Close button  -->
      <button class="close-btn" @click="$emit('close')">Close</button>

      <!-- Modal title -->
      <h3>Delete Customer</h3>

      <!-- Display customer fields -->
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


      <!-- "Confirm Deletion" button that calls handleConfirm method  -->
      <button @click="handleConfirm">Confirm Deletion</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AdminDeleteCustomerModal',
  props: {
    isVisible: {
      type: Boolean,
      default: false
    },
    customer: {
      type: Object,
      default: () => ({})
    }
  },
  emits: ['close', 'confirm-delete'],
  methods: {

    handleConfirm() {
      if (confirm('Are you sure you want to delete this customer?')) {
        this.$emit('confirm-delete', this.customer);
      }
    }
  }
}
</script>

<style scoped>
/* Styles for the overlay and modal content */
.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.5);
}

/* Main modal container styling */
.modal-content {
  background-color: white;
  width: 400px;
  margin: 100px auto;
  padding: 1rem;
  border-radius: 6px;
  text-align: left;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

/* Close button styling */
.close-btn {
  float: right;
  cursor: pointer;
  background: none;
  border: none;
  font-size: 1.2rem;
}
</style>