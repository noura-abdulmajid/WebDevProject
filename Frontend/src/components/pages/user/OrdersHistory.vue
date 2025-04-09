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
      <tr v-for="(order, index) in filteredOrders" :key="order.O_ID">
        <td><input type="checkbox" v-model="selectedOrders" :value="order.O_ID" /></td>
        <td>{{ index + 1 }}</td>
        <td>{{ order.O_ID }}</td>
        <td>{{ order.customer_name }}</td>
        <td>{{ formatDate(order.order_date) }}</td>
        <td>${{ order.total_payment }}</td>
        <td>{{ order.payment_status }}</td>
        <td>{{ order.delivery_status }}</td>
        <td>
          <button @click="openActionMenu(order.O_ID)">⋮</button>
          <div v-if="actionMenuId === order.O_ID" class="action-menu">
            <button @click="viewOrder(order.O_ID)">View</button>
            <button @click="editOrder(order.O_ID)">Edit</button>
            <button @click="deleteOrder(order.O_ID)">Delete</button>
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
import { ref, onMounted } from 'vue';
import axiosClient from '@/services/axiosClient';

export default {
  name: 'AdminOrders',
  setup() {
    const orders = ref([]);
    const filteredOrders = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const search = ref('');
    const paymentStatus = ref('');
    const deliveryStatus = ref('');
    const selectedOrders = ref([]);
    const selectAll = ref(false);
    const actionMenuId = ref(null);

    const fetchOrders = async () => {
      try {
        loading.value = true;
        error.value = null;
        const response = await axiosClient.get('/api/admin/orders');
        if (response.data && response.data.orders) {
          orders.value = response.data.orders.map(order => ({
            ...order.order,
            items: order.ordered_items,
            delivery_status: order.shipped?.delivery_status || 'pending'
          }));
          filteredOrders.value = [...orders.value];
        } else {
          orders.value = [];
          filteredOrders.value = [];
        }
      } catch (err) {
        error.value = 'Failed to fetch orders. Please try again later.';
        console.error('Error fetching orders:', err);
      } finally {
        loading.value = false;
      }
    };

    const applyFilters = () => {
      filteredOrders.value = orders.value.filter(order => {
        return (
            (!search.value || order.O_ID.toString().includes(search.value) || order.customer_name.includes(search.value)) &&
            (!paymentStatus.value || order.payment_status === paymentStatus.value) &&
            (!deliveryStatus.value || order.delivery_status === deliveryStatus.value)
        );
      });
    };

    const toggleAll = () => {
      selectedOrders.value = selectAll.value ? filteredOrders.value.map(order => order.O_ID) : [];
    };

    const openActionMenu = (id) => {
      actionMenuId.value = actionMenuId.value === id ? null : id;
    };

    const viewOrder = (id) => {
      window.location.href = `/admin/orders/${id}`;
    };

    const editOrder = (id) => {
      window.location.href = `/admin/orders/${id}/edit`;
    };

    const deleteOrder = (id) => {
      // Call API to delete the order
    };

    const bulkUpdatePayment = () => {
      // Call API to update payment status of selected orders
    };

    const bulkUpdateShipping = () => {
      // Call API to update shipping status of selected orders
    };

    const bulkDeleteOrders = () => {
      // Call API to delete selected orders
    };

    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString();
    };

    onMounted(() => {
      fetchOrders();
    });

    return {
      orders,
      filteredOrders,
      search,
      paymentStatus,
      deliveryStatus,
      selectedOrders,
      selectAll,
      actionMenuId,
      applyFilters,
      toggleAll,
      openActionMenu,
      viewOrder,
      editOrder,
      deleteOrder,
      bulkUpdatePayment,
      bulkUpdateShipping,
      bulkDeleteOrders,
      formatDate
    };
  }
};
</script>

<style>
.admin-orders {
  padding: 24px;
  font-family: 'Inter', sans-serif;
}

h1 {
  font-size: 28px;
  margin-bottom: 20px;
  color: #2B3674;
}

.filters,
.bulk-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 20px;
}

.filters input,
.filters select {
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
  min-width: 220px;
}

.filters button,
.bulk-actions button {
  background-color: #4318FF;
  color: white;
  border: none;
  padding: 8px 16px;
  font-weight: 500;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.filters button:hover,
.bulk-actions button:hover {
  background-color: #3614CC;
}

.orders-table {
  width: 100%;
  border-collapse: collapse;
  overflow-x: auto;
}

.orders-table th,
.orders-table td {
  border: 1px solid #E0E0E0;
  padding: 12px;
  text-align: left;
  font-size: 14px;
}

.orders-table thead {
  background-color: #F5F7FA;
}

.orders-table tbody tr:hover {
  background-color: #F0F4FF;
}

.orders-table button {
  background: none;
  border: none;
  font-size: 18px;
  cursor: pointer;
}

.action-menu {
  position: absolute;
  background-color: white;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  border-radius: 8px;
  padding: 8px;
  display: flex;
  flex-direction: column;
  gap: 6px;
  z-index: 999;
}

.action-menu button {
  padding: 6px 12px;
  background-color: #F4F7FE;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  text-align: left;
}

.action-menu button:hover {
  background-color: #E0E7FF;
}

@media (max-width: 768px) {
  .orders-table {
    display: block;
    overflow-x: auto;
  }

  .orders-table table {
    width: 100%;
    min-width: 800px;
  }
}

</style>