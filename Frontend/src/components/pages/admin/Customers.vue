<template>
  <h1>Customers Management</h1>

  <!-- Search & Bulk Actions -->
  <div class="top-bar">
  <div class="search-container">
    <input type="text" v-model="searchQuery" placeholder="Name, E-mail, Phone Number"/>
    <button @click="fetchCustomers">🔍</button>
  </div>

    <div class="button-group">
      <button class="add-customer-btn" @click="showModal = true">
        ➕ Add Customer
      </button>
      <button @click="bulkDeleteCustomers" class="bulk-delete-btn" :disabled="selectedCustomers.length === 0">
        🗑️ Bulk Delete ({{ selectedCustomers.length }})
      </button>
    </div>
  </div>



  <!-- Add Customer Modal -->
  <AdminAddCustomerModal :isVisible="showModal" @close="showModal = false" @customerAdded="fetchCustomers"/>
  <!-- Add View Customer Modal -->
  <AdminViewCustomerModal :isVisible="viewModalVisible" :customer="customerToView" @close="viewModalVisible = false"/>
  <!-- Add Delete Customer Modal -->
  <AdminDeleteCustomerModal :isVisible="showDeleteModal" :customer="customerToDelete" @close="showDeleteModal = false"
                            @confirm-delete="onConfirmDelete"/>

  <!-- Customers Table -->
  <div class="customers-table">
    <table>
      <thead>
      <tr>
        <th><input type="checkbox" @change="toggleSelectAll" v-model="selectAll"/></th>
        <th>No.</th>
        <th>Name-Surname</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Orders</th>
        <th>Amount Spent</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(customer ,index) in filteredCustomers" :key="customer.id">
        <td><input type="checkbox" v-model="selectedCustomers" :value="customer.id"/></td>
        <td>{{ index + 1 }}</td>
        <td>{{ customer.first_name }} {{ customer.surname }}</td>
        <td>{{ customer.email_address }}</td>
        <td>{{ customer.tel_no }}</td>
        <td>{{ customer.orders.total_orders }}
          <span
              class="tooltip"
              :title="getOrderTooltip(customer.orders)"
          >
            (Details)
          </span>
        </td>
        <td>£{{ customer.orders.total_spent.toFixed(2) }}</td>
        <td class="action-buttons">
          <button @click="viewCustomer(customer.id)" class="view">👁️ View</button>
          <button @click="editCustomer(customer.id)" class="edit">✏️ Edit</button>
          <button @click="deleteCustomer(customer.id)" class="delete">🗑️ Delete</button>
        </td>
      </tr>
      <tr v-if="filteredCustomers.length === 0">
        <td colspan="7" class="no-data">No customers available.</td>
      </tr>
      </tbody>
    </table>
  </div>

  <!-- Error Message -->
  <p v-if="error" class="error">{{ error }}</p>
</template>

<script src="../../../scripts/pages/admin/CustomersScript.js"></script>
<style scoped src="../../../styles/pages/admin/CustomersStyle.css"></style>
