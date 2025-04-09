<template>
  <div class="admin-orders">
    <h1>Order Management</h1>

    <!-- Filters -->
    <div class="filters">
      <input type="text" v-model="search" placeholder="Search by Order No or Customer Name" />
      <select v-model="paymentStatus">
        <option value="">All Payment Status</option>
        <option value="paid">Paid</option>
        <option value="unpaid">Unpaid</option>
      </select>
      <select v-model="deliveryStatus">
        <option value="">All Delivery Status</option>
        <option value="pending">Pending</option>
        <option value="shipped">Shipped</option>
        <option value="delivered">Delivered</option>
      </select>
      <button @click="applyFilters">Apply</button>
    </div>

    <!-- Orders Table -->
    <table class="orders-table">
      <thead>
      <tr>
        <th><input type="checkbox" @change="toggleAll" v-model="selectAll" /></th>
        <th>No.</th>
        <th>Order No.</th>
        <th>Customer</th>
        <th>Date</th>
        <th>Total Amount</th>
        <th>Payment Status</th>
        <th>Delivery Status</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(order, index) in filteredOrders" :key="order.id">
        <td><input type="checkbox" v-model="selectedOrders" :value="order.id" /></td>
        <td>{{ index + 1 }}</td>
        <td>{{ order.order_number }}</td>
        <td>{{ order.customer_name }}</td>
        <td>{{ order.date }}</td>
        <td>{{ order.total_amount }}</td>
        <td>{{ order.payment_status }}</td>
        <td>{{ order.delivery_status }}</td>
        <td>
          <button @click="openActionMenu(order.id)">⋮</button>
          <div v-if="actionMenuId === order.id" class="action-menu">
            <button @click="viewOrder(order.id)">View</button>
            <button @click="editOrder(order.id)">Edit</button>
            <button @click="deleteOrder(order.id)">Delete</button>
          </div>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Bulk Actions -->
    <div class="bulk-actions">
      <button @click="bulkUpdatePayment">Update Payment Status</button>
      <button @click="bulkUpdateShipping">Update Delivery Status</button>
      <button @click="bulkDeleteOrders">Delete Selected Orders</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      search: '',
      paymentStatus: '',
      deliveryStatus: '',
      orders: [],
      filteredOrders: [],
      selectedOrders: [],
      selectAll: false,
      actionMenuId: null
    }
  },
  methods: {
    fetchOrders() {
      // Call API to fetch orders
      // axios.get('/api/admin/orders').then(response => {
      //   this.orders = response.data;
      //   this.filteredOrders = this.orders;
      // });
    },
    applyFilters() {
      this.filteredOrders = this.orders.filter(order => {
        return (
            (!this.search || order.order_number.includes(this.search) || order.customer_name.includes(this.search)) &&
            (!this.paymentStatus || order.payment_status === this.paymentStatus) &&
            (!this.deliveryStatus || order.delivery_status === this.deliveryStatus)
        );
      });
    },
    toggleAll() {
      this.selectedOrders = this.selectAll ? this.filteredOrders.map(order => order.id) : [];
    },
    openActionMenu(id) {
      this.actionMenuId = this.actionMenuId === id ? null : id;
    },
    viewOrder(id) {
      // Navigate to order detail page
      this.$router.push(`/admin/orders/${id}`);
    },
    editOrder(id) {
      // Navigate to order edit page
      this.$router.push(`/admin/orders/${id}/edit`);
    },
    deleteOrder(id) {
      // Call API to delete the order
    },
    bulkUpdatePayment() {
      // Call API to update payment status of selected orders
    },
    bulkUpdateShipping() {
      // Call API to update shipping status of selected orders
    },
    bulkDeleteOrders() {
      // Call API to delete selected orders
    }
  },
  mounted() {
    this.fetchOrders();
  }
}
</script>

<style>
.admin-orders {
  padding: 20px;
}
.orders-table {
  width: 100%;
  border-collapse: collapse;
}
.orders-table th,
.orders-table td {
  border: 1px solid #ddd;
  padding: 8px;
}
.filters,
.bulk-actions {
  margin-bottom: 15px;
  display: flex;
  gap: 10px;
}
.action-menu {
  display: flex;
  flex-direction: column;
  background-color: #f3f3f3;
  border: 1px solid #ccc;
  position: absolute;
}
</style>
