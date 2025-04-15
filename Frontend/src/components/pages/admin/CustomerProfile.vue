<template>
  <div class="customer-profile">
    <h1>Customer Profile</h1>

    <!-- Loading State -->
    <div v-if="loading">Loading...</div>

    <!-- Error Message (Displays Placeholder Data if API Fails) -->
    <div v-else-if="error">
      <p class="error-message">{{ error }}</p>
      <p class="info">⚠ Displaying placeholder data.</p>
      <div class="customer-details">
        <p><strong>Full Name:</strong> Placeholder Name</p>
        <p><strong>Email:</strong> example@email.com</p>
        <p><strong>Phone:</strong> 000-000-0000</p>
        <p><strong>Address:</strong> Not Available</p>
        <p><strong>Total Orders:</strong> 0</p>
        <p><strong>Total Spent:</strong> £0.00</p>
      </div>
    </div>

    <!-- Display Customer Details -->
    <div v-else>
      <div class="customer-details">
        <p><strong>Full Name:</strong> {{ customer.first_name }} {{ customer.surname }}</p>
        <p><strong>Email:</strong> {{ customer.email_address }}</p>
        <p><strong>Phone:</strong> {{ customer.tel_no || "Not Provided" }}</p>
        <p><strong>Shipping Address:</strong> {{ customer.shipping_address || "Not Provided" }}</p>
        <p><strong>Billing Address:</strong> {{ customer.billing_address || "Not Provided" }}</p>
      </div>


    </div>

    <div class="button-group">
      <button @click="toggleEditModal" class="edit-button">✏️ Edit Customer</button>
    </div>

    <!-- Order Table -->
    <h2>Order History</h2>
    <div class="order-table">
      <table>
        <thead>
        <tr>
          <th>Order ID</th>
          <th>Date</th>
          <th>Item Quantity</th>
          <th>Total Purchase</th>
          <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="order in orders" :key="order.O_ID">
          <td>{{ order.O_ID }}</td>
          <td>{{ order.order_date }}</td>
          <td>{{ order.itemQuantity }}</td>
          <td>£{{ order.total_payment.toFixed(2) }}</td>
          <td class="action-buttons">
            <button @click="viewOrder(order.O_ID)" class="view">👁️ View</button>
            <button @click="openEditOrderModal(order.O_ID)" class="edit">✏️ Edit</button>
            <button @click="triggerDeleteOrder(order.O_ID)" class="delete">🗑️ Delete</button>
          </td>
        </tr>

        <tr v-if="orders.length === 0">
          <td colspan="5" class="no-data">No orders found.</td>
        </tr>
        </tbody>
      </table>
    </div>

    <!-- Dialog for Viewing Order Details -->
    <div v-if="selectedOrder" class="order-detail-dialog">
      <div class="dialog-overlay" @click="closeOrderDetail"></div>
      <div class="dialog-content">
        <h3>Order #{{ selectedOrder.O_ID }}</h3>
        <p><strong>Order Date:</strong> {{ selectedOrder.order_date }}</p>
        <p><strong>Shipping Address:</strong> {{ selectedOrder.shipping_address }}</p>
        <p><strong>Subtotal:</strong> £{{ selectedOrder.subtotal.toFixed(2) }}</p>
        <p><strong>Delivery Charge:</strong> £{{ selectedOrder.delivery_charge.toFixed(2) }}</p>
        <p><strong>Total Payment:</strong> £{{ selectedOrder.total_payment.toFixed(2) }}</p>
        <button @click="closeOrderDetail" class="close-dialog-button">Close</button>
      </div>
    </div>

    <!-- Dialog for Editing Order -->
    <div v-if="showEditOrderModal" class="edit-order-dialog">
      <div class="dialog-overlay" @click="closeEditOrderModal"></div>
      <div class="dialog-content">
        <h3>Edit Order #{{ editOrderData.O_ID }}</h3>

        <!-- Edit Order Form -->
        <form @submit.prevent="editOrder">
          <!-- Order Date -->
          <div class="form-group">
            <label for="order_date">Order Date:</label>
            <input
                v-model="editOrderData.order_date"
                type="date"
                id="order_date"
                required
            />
          </div>

          <!-- Shipping Address -->
          <div class="form-group">
            <label for="shipping_address">Shipping Address:</label>
            <textarea
                v-model="editOrderData.shipping_address"
                id="shipping_address"
                required
            ></textarea>
          </div>

          <!-- Subtotal -->
          <div class="form-group">
            <label for="subtotal">Subtotal (£):</label>
            <input
                v-model.number="editOrderData.subtotal"
                type="number"
                id="subtotal"
                step="0.01"
                required
            />
          </div>

          <!-- Delivery Charge -->
          <div class="form-group">
            <label for="delivery_charge">Delivery Charge (£):</label>
            <input
                v-model.number="editOrderData.delivery_charge"
                type="number"
                id="delivery_charge"
                step="0.01"
                required
            />
          </div>

          <!-- Total Payment -->
          <div class="form-group">
            <label for="total_payment">Total Payment (£):</label>
            <input
                v-model.number="editOrderData.total_payment"
                type="number"
                id="total_payment"
                step="0.01"
                required
            />
          </div>

          <!-- Action Buttons -->
          <div class="form-actions">
            <button type="submit" class="save-button">Save</button>
            <button
                type="button"
                @click="closeEditOrderModal"
                class="cancel-button"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirm Dialog -->
    <div v-if="showDeleteConfirmDialog" class="delete-confirm-dialog">
      <div class="dialog-overlay" @click="closeDeleteConfirmDialog"></div>
      <div class="dialog-content">
        <p>Are you sure you want to delete this order?</p>
        <div class="dialog-actions">
          <button class="confirm-button" @click="confirmDeleteOrder">Confirm</button>
          <button class="cancel-button" @click="closeDeleteConfirmDialog">Cancel</button>
        </div>
      </div>
    </div>


    <button @click="goBack" class="back-button">🔙 Back to Customers</button>

    <!-- Edit Customer Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal">
        <h2>Edit Customer</h2>
        <form @submit.prevent="editCustomer">
          <!-- First Name -->
          <div class="form-group">
            <label for="first_name">First Name:</label>
            <input v-model="editCustomerData.first_name" type="text" id="first_name"/>
          </div>

          <!-- Surname -->
          <div class="form-group">
            <label for="surname">Surname:</label>
            <input v-model="editCustomerData.surname" type="text" id="surname"/>
          </div>

          <!-- Email Address -->
          <div class="form-group">
            <label for="email">Email Address:</label>
            <input v-model="editCustomerData.email_address" type="email" id="email"/>
          </div>

          <!-- Phone Number -->
          <div class="form-group">
            <label for="tel_no">Phone Number:</label>
            <input v-model="editCustomerData.tel_no" type="tel" id="tel_no"/>
          </div>

          <!-- Shipping Address -->
          <div class="form-group">
            <label for="shipping_address">Shipping Address:</label>
            <textarea v-model="editCustomerData.shipping_address" id="shipping_address"></textarea>
          </div>

          <!-- Billing Address -->
          <div class="form-group">
            <label for="billing_address">Billing Address:</label>
            <textarea v-model="editCustomerData.billing_address" id="billing_address"></textarea>
          </div>

          <!-- Action Buttons -->
          <div class="form-actions">
            <button type="submit" class="save-button">Save</button>
            <button type="button" @click="toggleEditModal" class="cancel-button">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</template>

<script src="../../../scripts/pages/admin/CustomerProfileScript.js"></script>
<style scoped src="../../../styles/pages/admin/CustomerProfileStyle.css"></style>
