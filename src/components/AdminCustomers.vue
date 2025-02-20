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
      <h1>Customers</h1>

      <!-- ✅ Search Bar & Add Customers Button -->
      <div class="header-bar">
        <input type="text" v-model="searchQuery" placeholder="Name, E-mail, Phone" />
        <button @click="showAddCustomerForm = true" class="add-btn">+ Add Customers</button>
      </div>

      <!-- ✅ Add Customer Modal -->
      <div v-if="showAddCustomerForm" class="modal">
        <div class="modal-content">
          <h2>Add New Customer</h2>
          <input type="text" v-model="newCustomer.name" placeholder="Full Name" />
          <input type="email" v-model="newCustomer.email" placeholder="Email" />
          <input type="text" v-model="newCustomer.phone" placeholder="Phone" />
          <button @click="addCustomer">Save</button>
          <button @click="showAddCustomerForm = false">Cancel</button>
        </div>
      </div>

      <!-- ✅ Customers Table (Always Visible) -->
      <table class="customers-table">
        <thead>
        <tr>
          <th>Name-Surname</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Orders</th>
          <th>Amount Spent</th>
          <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="customer in filteredCustomers" :key="customer.id">
          <td>{{ customer.name }}</td>
          <td>{{ customer.email }}</td>
          <td>{{ customer.phone }}</td>
          <td>{{ customer.orders }} Orders</td>
          <td>£{{ customer.amount_spent.toFixed(2) }}</td>
          <td class="action-column">
            <button @click="toggleActionMenu(customer.id)" class="action-btn">⋮</button>
            <div v-if="activeActionMenu === customer.id" class="action-menu">
              <button @click="viewCustomer(customer.id)">👁️ View</button>
              <button @click="editCustomer(customer.id)">✏️ Edit</button>
              <button @click="deleteCustomer(customer.id)">🗑️ Delete</button>
            </div>
          </td>
        </tr>
        <!-- ✅ Fallback Row When No Data Available -->
        <tr v-if="filteredCustomers.length === 0">
          <td colspan="6" class="no-data">No customer data available.</td>
        </tr>
        </tbody>
      </table>

      <!-- ✅ Error Message (Table Remains Visible) -->
      <p v-if="error" class="error">{{ error }}</p>
    </main>
  </div>
</template>

<script src="../scripts/AdminCustomersScript.js"></script>
<style scoped src="../styles/GlobalStyle.css"></style>
<style scoped src="../styles/AdminCustomersStyle.css"></style>
