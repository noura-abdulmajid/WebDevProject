<template>
  <div class="orders-history">
    <div class="content-wrapper">
      <div class="orders-container">
        <h2>Order History</h2>
        <div v-if="loading" class="loading">Loading orders...</div>
        <div v-else-if="error" class="error">{{ error }}</div>
        <div v-else-if="orders.length === 0" class="no-orders">No orders found</div>
        <div v-else class="orders-list">
          <div v-for="order in orders" :key="order.O_ID" class="order-card">
            <div class="order-header">
              <span class="order-number">Order #{{ order.O_ID }}</span>
              <span :class="['order-status', order.delivery_status]">{{ order.delivery_status }}</span>
            </div>
            <div class="order-details">
              <div class="order-date">Order Date: {{ formatDate(order.order_date) }}</div>
              <div class="order-items">
                <div v-for="item in order.items" :key="item.P_ID" class="order-item">
                  <span class="item-name">{{ item.name }}</span>
                  <span class="item-quantity">x{{ item.quantity }}</span>
                  <span class="item-price">${{ item.price }}</span>
                </div>
              </div>
              <div class="order-total">
                <span>Subtotal:</span>
                <span>${{ order.subtotal }}</span>
              </div>
              <div class="order-total">
                <span>Shipping:</span>
                <span>${{ order.delivery_charge }}</span>
              </div>
              <div class="order-total total">
                <span>Total:</span>
                <span>${{ order.total_payment }}</span>
              </div>
            </div>
            <div class="order-actions">
              <button v-if="canRequestRefund(order)" 
                      @click="showRefundForm(order)" 
                      class="refund-btn">
                Request Refund
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Refund Form Modal -->
    <div v-if="showRefundModal" class="modal">
      <div class="modal-content">
        <h3>Request Refund</h3>
        <p>Order #{{ selectedOrder?.O_ID }}</p>
        <div class="form-group">
          <label for="refundReason">Refund Reason:</label>
          <textarea id="refundReason" v-model="refundReason" required></textarea>
        </div>
        <div class="modal-actions">
          <button @click="closeRefundModal" class="cancel-btn">Cancel</button>
          <button @click="submitRefund" class="submit-btn">Submit</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axiosClient from '@/services/axiosClient';
import apiConfig from '@/config/apiURL';

export default {
  name: 'OrdersHistory',
  setup() {
    const orders = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const showRefundModal = ref(false);
    const selectedOrder = ref(null);
    const refundReason = ref('');

    const fetchOrders = async () => {
      try {
        loading.value = true;
        error.value = null;
        const response = await axiosClient.get('/DashShoe/profile/orders');
        if (response.data && response.data.orders) {
          orders.value = response.data.orders.map(order => ({
            ...order.order,
            items: order.ordered_items,
            delivery_status: order.shipped?.delivery_status || 'pending'
          }));
        } else {
          orders.value = [];
        }
      } catch (err) {
        error.value = 'Failed to fetch orders. Please try again later.';
        console.error('Error fetching orders:', err);
      } finally {
        loading.value = false;
      }
    };

    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString();
    };

    const canRequestRefund = (order) => {
      return ['shipped', 'delivered'].includes(order.delivery_status);
    };

    const showRefundForm = (order) => {
      selectedOrder.value = order;
      showRefundModal.value = true;
    };

    const closeRefundModal = () => {
      showRefundModal.value = false;
      selectedOrder.value = null;
      refundReason.value = '';
    };

    const submitRefund = async () => {
      if (!selectedOrder.value || !refundReason.value) {
        alert('Please provide a refund reason');
        return;
      }

      try {
        const response = await axiosClient.post('/DashShoe/profile/refunds', {
          order_id: selectedOrder.value.O_ID,
          reason: refundReason.value
        });

        if (response.status === 201) {
          alert('Refund request submitted successfully');
          closeRefundModal();
          fetchOrders();
        }
      } catch (err) {
        console.error('Error submitting refund:', err);
        alert('Failed to submit refund request. Please try again later.');
      }
    };

    onMounted(() => {
      fetchOrders();
    });

    return {
      orders,
      loading,
      error,
      showRefundModal,
      selectedOrder,
      refundReason,
      formatDate,
      canRequestRefund,
      showRefundForm,
      closeRefundModal,
      submitRefund
    };
  }
};
</script>

<style scoped>
.orders-history {
  padding: 20px;
  margin-top: 20px;
  width: 100%;
  height: calc(100vh - 100px);
  overflow-y: auto;
  position: relative;
}

.content-wrapper {
  max-width: 1200px;
  margin: 0 auto;
  height: 100%;
}

.orders-container {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  height: 100%;
}

h2 {
  margin-bottom: 20px;
  color: #333;
}

.loading, .error, .no-orders {
  text-align: center;
  padding: 20px;
  font-size: 1.1em;
}

.error {
  color: #dc3545;
}

.orders-list {
  display: grid;
  gap: 20px;
}

.order-card {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  background: white;
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
  padding-bottom: 10px;
  border-bottom: 1px solid #eee;
}

.order-number {
  font-weight: bold;
  font-size: 1.1em;
}

.order-status {
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.9em;
}

.order-status.pending {
  background-color: #fff3cd;
  color: #856404;
}

.order-status.shipped {
  background-color: #cce5ff;
  color: #004085;
}

.order-status.delivered {
  background-color: #d4edda;
  color: #155724;
}

.order-status.canceled {
  background-color: #f8d7da;
  color: #721c24;
}

.order-details {
  margin-bottom: 15px;
}

.order-date {
  color: #666;
  margin-bottom: 10px;
}

.order-items {
  margin-bottom: 15px;
}

.order-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 5px;
}

.order-total {
  display: flex;
  justify-content: space-between;
  margin-bottom: 5px;
}

.order-total.total {
  font-weight: bold;
  font-size: 1.1em;
  margin-top: 10px;
  padding-top: 10px;
  border-top: 1px solid #eee;
}

.order-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.refund-btn {
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.refund-btn:hover {
  background-color: #c82333;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 90%;
  max-width: 500px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
}

.form-group textarea {
  width: 100%;
  min-height: 100px;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.cancel-btn, .submit-btn {
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.cancel-btn {
  background-color: #6c757d;
  color: white;
  border: none;
}

.submit-btn {
  background-color: #28a745;
  color: white;
  border: none;
}

.cancel-btn:hover {
  background-color: #5a6268;
}

.submit-btn:hover {
  background-color: #218838;
}
</style>