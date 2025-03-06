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
        <p><strong>Full Name:</strong> {{ customer.name }}</p>
        <p><strong>Email:</strong> {{ customer.email }}</p>
        <p><strong>Phone:</strong> {{ customer.phone }}</p>
        <p><strong>Address:</strong> {{ customer.address || "Not Provided" }}</p>
        <p><strong>Total Orders:</strong> {{ customer.orders }}</p>
        <p><strong>Total Spent:</strong> £{{ customer.amount_spent.toFixed(2) }}</p>
      </div>


    </div>

    <div class="button-group">
      <button @click="editCustomer" class="edit-button">✏️ Edit Customer</button>
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
        <tr v-for="order in orders" :key="order.id">
          <td>{{ order.id }}</td>
          <td>{{ order.date }}</td>
          <td>{{ order.items }}</td>
          <td>£{{ order.total.toFixed(2) }}</td>
          <td class="action-buttons">
            <button @click="viewOrder(order.id)" class="view">👁️ View</button>
            <button @click="editOrder(order.id)" class="edit">✏️ Edit</button>
            <button @click="deleteOrder(order.id)" class="delete">🗑️ Delete</button>
          </td>
        </tr>
        <tr v-if="orders.length === 0">
          <td colspan="5" class="no-data">No orders found.</td>
        </tr>
        </tbody>
      </table>
    </div>

    <button @click="goBack" class="back-button">🔙 Back to Customers</button>
  </div>

</template>

<script src="../../../scripts/pages/admin/CustomerProfileScript.js"></script>
<style scoped src="../../../styles/pages/admin/CustomerProfileStyle.css"></style>
