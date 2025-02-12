<template>
  <div class="admin-dashboard">
    <!-- ✅ Sidebar -->
    <aside class="sidebar">
      <h2 class="nav-links">Admin Panel</h2>
      <ul class="nav-links">
        <li><router-link to="/admin-dashboard">Dashboard</router-link></li>
        <li><router-link to="/admin-customers" class="active">Customers</router-link></li>
        <li><router-link to="/admin-orders">Orders</router-link></li>
        <li><router-link to="/admin-products">Products</router-link></li>
        <li><router-link to="/admin-settings">Settings</router-link></li>
      </ul>
    </aside>

    <!-- ✅ Main Content -->
    <main class="content">
      <h1>Customer Profile</h1>

      <!-- ✅ Loading & Error Messages -->
      <p v-if="loading">Loading customer details...</p>
      <p v-if="error" class="error">{{ error }}</p>

      <!-- ✅ Customer Details -->
      <div v-if="!loading && !error" class="customer-details">
        <h2>Personal Information</h2>
        <label>First Name:</label>
        <input type="text" v-model="customer.first_name" />

        <label>Surname:</label>
        <input type="text" v-model="customer.surname" />

        <label>Email:</label>
        <input type="email" v-model="customer.email_address" disabled />

        <label>Phone:</label>
        <input type="text" v-model="customer.tel_no" />

        <h2>Address Information</h2>
        <label>Shipping Address:</label>
        <textarea v-model="customer.shipping_address"></textarea>

        <label>Billing Address:</label>
        <textarea v-model="customer.billing_address"></textarea>

        <label>Date Joined:</label>
        <input type="text" v-model="customer.date_joined" disabled />

        <!-- ✅ Save Changes Button -->
        <button @click="updateCustomer">Save Changes</button>
      </div>

      <!-- ✅ Order History -->
      <h2>Order History</h2>
      <table class="orders-table">
        <thead>
        <tr>
          <th>Order No.</th>
          <th>Date</th>
          <th>Total</th>
          <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="order in orders" :key="order.id">
          <td>{{ order.id }}</td>
          <td>{{ formatDate(order.date) }}</td>
          <td>฿{{ order.total.toFixed(2) }}</td>
          <td>{{ order.status }}</td>
        </tr>
        </tbody>
      </table>
    </main>
  </div>
</template>

<script src="../scripts/AdminCustomerProfileScript.js"></script>
<style scoped src="../styles/AdminCustomerProfileStyle.css"></style>
