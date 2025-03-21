<template>
  <div v-if="isVisible" class="modal-overlay">
    <div class="modal-content">

      <!-- Close Button -->
      <button class="close-btn" @click="$emit('close')">✖</button>

      <!-- Title -->
      <h3 class="modal-title">Customer Details</h3>

      <div class="modal-body">
        <!-- Customer Fields -->
        <p>
          <strong>C_ID:</strong>
          <span>{{ customer.C_ID }}</span>
        </p>
        <p>
          <strong>Name:</strong>
          <span>{{ customer.first_name }} {{ customer.surname }}</span>
        </p>
        <p>
          <strong>Email:</strong>
          <span>{{ customer.email_address }}</span>
        </p>
        <p>
          <strong>Phone:</strong>
          <span>{{ customer.tel_no }}</span>
        </p>
        <p>
          <strong>Shipping Address:</strong>
          <span>{{ customer.shipping_address }}</span>
        </p>
        <p>
          <strong>Billing Address:</strong>
          <span>{{ customer.billing_address }}</span>
        </p>
      </div>
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
/* Modal Overlay: Adds a blur, darkens the background */
.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(3px); /* Adds a soft blur effect */
  display: flex;
  justify-content: center;
  align-items: center;
}

/* Modal Content: Modern design with better spacing */
.modal-content {
  background-color: #ffffff;
  width: 450px; /* Increased width for better layout */
  padding: 1.5rem 2rem;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  text-align: left;
  animation: fadeIn 0.3s ease; /* Add fade animation */
  position: relative; /* Ensure close button positions relative to modal */
  font-family: "Roboto", sans-serif; /* Modern font */
}

/* Modal Title: Styled for prominence */
.modal-title {
  font-size: 1.5rem;
  font-weight: 600;
  text-align: center;
  color: #333;
  margin-bottom: 20px;
}

/* Modal Body Section */
.modal-body p {
  margin: 0.5rem 0;
  display: flex;
  justify-content: space-between;
  font-size: 1rem;
  color: #555;
}

.modal-body p strong {
  font-weight: 600;
  color: #333;
}

.modal-body span {
  text-align: right;
  flex: 1;
  margin-left: 10px;
  color: #666;
}

/* Close Button */
.close-btn {
  position: absolute;
  top: 15px;
  right: 15px;
  background: none;
  border: none;
  font-size: 1.25rem;
  color: #555;
  cursor: pointer;
  transition: color 0.3s ease;
}

.close-btn:hover {
  color: #e74c3c;
}

/* Fade-in Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
