<template>
  <div class="orders-history">
    <h1>My Orders</h1>

    <!-- Loading state -->
    <div v-if="loading" class="loading">Loading...</div>

    <!-- Error state -->
    <div v-if="error" class="error">
      Failed to load orders. <button @click="fetchOrders">Retry</button>
    </div>

    <!-- Empty state -->
    <div v-if="!loading && !error && orders.length === 0" class="empty">
      <p>You haven't placed any orders yet.</p>
    </div>

    <!-- Orders grouped by status -->
    <div v-if="!loading && !error && orders.length > 0" class="statuses">
      <!-- Pending Orders -->
      <div v-if="pendingOrders.length > 0">
        <h2>Pending Orders</h2>
        <ul class="orders-list">≈
          <li v-for="order in pendingOrders" :key="order.id" class="order">
            <h3>Order #{{ order.id }}</h3>
            <p><strong>Date:</strong> {{ order.date }}</p>
            <p><strong>Total:</strong> ${{ order.total }}</p>
          </li>
        </ul>
      </div>

      <!-- Shipped Orders -->
      <div v-if="shippedOrders.length > 0">
        <h2>Shipped Orders</h2>
        <ul class="orders-list">
          <li v-for="order in shippedOrders" :key="order.id" class="order">
            <h3>Order #{{ order.id }}</h3>
            <p><strong>Date:</strong> {{ order.date }}</p>
            <p><strong>Total:</strong> ${{ order.total }}</p>
          </li>
        </ul>
      </div>

      <!-- Delivered Orders -->
      <div v-if="deliveredOrders.length > 0">
        <h2>Delivered Orders</h2>
        <ul class="orders-list">
          <li v-for="order in deliveredOrders" :key="order.id" class="order">
            <h3>Order #{{ order.id }}</h3>
            <p><strong>Date:</strong> {{ order.date }}</p>
            <p><strong>Total:</strong> ${{ order.total }}</p>
          </li>
        </ul>
      </div>

      <!-- Canceled Orders -->
      <div v-if="canceledOrders.length > 0">
        <h2>Canceled Orders</h2>
        <ul class="orders-list">
          <li v-for="order in canceledOrders" :key="order.id" class="order">
            <h3>Order #{{ order.id }}</h3>
            <p><strong>Date:</strong> {{ order.date }}</p>
            <p><strong>Total:</strong> ${{ order.total }}</p>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import axiosClient from "@/services/axiosClient.js";
import apiConfig from "@/config/apiURL.js";

// Data reference
const orders = ref([]);
const loading = ref(true);
const error = ref(null);

// Retrieve JWT token from local storage
const getToken = () => localStorage.getItem("jwt");

// Fetch orders from the API
const fetchOrders = async () => {
  loading.value = true;
  error.value = null;

  try {
    const token = getToken();
    if (!token) throw new Error("No authentication token found. Please log in.");

    // Send GET request to fetch orders
    const response = await axiosClient.get(apiConfig.userProfile.ordersHistory, {
      headers: { Authorization: `Bearer ${token}` },
    });

    // Format API response into a usable structure
    orders.value = response.data.orders.map((order) => ({
      id: order.order.O_ID,
      date: order.order.order_date,
      total: order.order.total_payment,
      status: order.shipped.delivery_status,
    }));
  } catch (err) {
    console.error(err);
    error.value = err.message || "Failed to fetch orders.";
  } finally {
    loading.value = false;
  }
};

// Group orders by delivery status
const pendingOrders = computed(() =>
    orders.value.filter((order) => order.status === "pending")
);
const shippedOrders = computed(() =>
    orders.value.filter((order) => order.status === "shipped")
);
const deliveredOrders = computed(() =>
    orders.value.filter((order) => order.status === "delivered")
);
const canceledOrders = computed(() =>
    orders.value.filter((order) => order.status === "canceled")
);

// Fetch orders when the component is created
fetchOrders();
</script>

<style scoped>
/* Main container */
.orders-history {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  font-family: Arial, sans-serif;
  overflow-y: auto;
  height: 600px;
}

/* Title styling */
.orders-history h1 {
  text-align: center;
  margin-bottom: 20px;
  font-size: 2rem;
  color: #333;
}

/* States: Loading, Error, and Empty */
.loading,
.error,
.empty {
  text-align: center;
  margin: 20px 0;
  font-size: 1.2rem;
}

/* Button styles */
button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 5px 10px;
  margin-top: 10px;
  cursor: pointer;
  border-radius: 5px;
}

button:hover {
  background-color: #0056b3;
}

/* Order List styles */
.orders-list {
  list-style: none;
  padding: 0;
}

.order {
  border: 1px solid #ddd;
  border-radius: 5px;
  margin-bottom: 20px;
  padding: 15px;
  background-color: #f9f9f9;
}

.order h3 {
  margin: 0 0 10px;
  color: #007bff;
}

.order p {
  margin: 5px 0;
}
</style>