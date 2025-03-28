<template>
<div>
    <header>
    <div class="logo-container">
        <img src="/logo.png" class="logo" alt="company logo">
    </div>
    </header>

    <div class="main-container">
    <h1>Order List</h1>
    <div class="search-filter">
        <input
        type="text"
        v-model="searchQuery"
        placeholder="Search by product..."
        class="search-input"
        >
        <select v-model="statusFilter" class="status-select">
        <option value="">All Statuses</option>
        <option value="placed">Placed</option>
        <option value="shipped">Shipped</option>
        <option value="delivered">Delivered</option>
        <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <button @click="processAllPlacedOrders">Process All Placed Orders</button>
    <button @click="openAddOrderModal">Add New Order</button>

    <table>
        <thead>
        <tr>
            <th>Order ID</th>
            <th>Product</th>
            <th>Status</th>
            <th>Order Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="order in filteredOrders" :key="order.id">
            <td>{{ order.id }}</td>
            <td>{{ order.product }}</td>
            <td>{{ order.status }}</td>
            <td>{{ formatDate(order.orderDate) }}</td>
            <td>
            <button v-if="order.status === 'placed'" @click="processOrder(order.id)">
                Process Order
            </button>
            <button @click="printLabel(order)">Print Label</button>
            <button @click="openEditOrderModal(order)">Edit</button>
            <button @click="deleteOrder(order.id)">Delete</button>
            </td>
        </tr>
        </tbody>
    </table>

    <div v-if="isModalOpen" class="modal-overlay">
        <div class="modal">
        <h2>{{ isEditing ? 'Edit Order' : 'Add New Order' }}</h2>
        <form @submit.prevent="isEditing ? updateOrder() : addNewOrder()">
            <div class="form-group">
            <label for="product">Product</label>
            <input type="text" id="product" v-model="currentOrder.product" required>
            </div>
            <div class="form-group">
            <label for="status">Status</label>
            <select id="status" v-model="currentOrder.status" required>
                <option value="placed">Placed</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
            </select>
            </div>
            <div class="form-group">
            <label for="orderDate">Order Date</label>
            <input type="date" id="orderDate" v-model="currentOrder.orderDate" required>
            </div>
            <div class="modal-actions">
            <button type="button" @click="closeModal">Cancel</button>
            <button type="submit">{{ isEditing ? 'Update' : 'Add' }}</button>
            </div>
        </form>
        </div>
    </div>

    <div v-if="printActive" class="print-view">
        <div class="label">
        <h2>Shipping Label</h2>
        <p>Order #: {{ printOrder.id }}</p>
        <p>Product: {{ printOrder.product }}</p>
        <p>Status: {{ printOrder.status }}</p>
        <p>Date: {{ formatDate(printOrder.orderDate) }}</p>
        <div class="barcode">*{{ printOrder.id }}*</div>
        </div>
    </div>
    </div>
</div>
</template>

<script>
import { ref, reactive, computed } from 'vue';

export default {
setup() {
    const orders = reactive([
    { id: 1, product: 'Nike Air Force 1 Low', status: 'placed', orderDate: '2025-02-25' },
    { id: 2, product: 'adidas Originals Handball Spezial', status: 'shipped', orderDate: '2025-02-25' }
    ]);

    const searchQuery = ref('');
    const statusFilter = ref('');
    const isModalOpen = ref(false);
    const isEditing = ref(false);
    const printActive = ref(false);
    const printOrder = reactive({ id: 0, product: '', status: '', orderDate: '' });
    const currentOrder = reactive({ id: 0, product: '', status: 'placed', orderDate: new Date().toISOString().split('T')[0] });

    const filteredOrders = computed(() => {
    return orders.filter(order => {
        const matchesSearch = order.product.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesStatus = statusFilter.value ? order.status === statusFilter.value : true;
        return matchesSearch && matchesStatus;
    });
    });

    const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
    };

    const printLabel = (order) => {
    Object.assign(printOrder, order);
    printActive.value = true;
    setTimeout(() => {
        window.print();
        printActive.value = false;
    }, 100);
    };

    const processOrder = (id) => {
    const order = orders.find(o => o.id === id);
    if (order) {
        order.status = 'shipped';
    }
    };

    const processAllPlacedOrders = () => {
    orders.forEach(order => {
        if (order.status === 'placed') {
        order.status = 'shipped';
        }
    });
    };

    const openAddOrderModal = () => {
    isEditing.value = false;
    currentOrder.id = orders.length ? Math.max(...orders.map(o => o.id)) + 1 : 1;
    currentOrder.product = '';
    currentOrder.status = 'placed';
    currentOrder.orderDate = new Date().toISOString().split('T')[0];
    isModalOpen.value = true;
    };

    const openEditOrderModal = (order) => {
    isEditing.value = true;
    Object.assign(currentOrder, JSON.parse(JSON.stringify(order)));
    isModalOpen.value = true;
    };

    const addNewOrder = () => {
    orders.push({ ...currentOrder });
    closeModal();
    };

    const updateOrder = () => {
    const index = orders.findIndex(o => o.id === currentOrder.id);
    if (index !== -1) {
        orders[index] = { ...currentOrder };
    }
    closeModal();
    };

    const deleteOrder = (id) => {
    const index = orders.findIndex(o => o.id === id);
    if (index !== -1) {
        orders.splice(index, 1);
    }
    };

    const closeModal = () => {
    isModalOpen.value = false;
    };

    return {
    orders,
    searchQuery,
    statusFilter,
    filteredOrders,
    isModalOpen,
    isEditing,
    printActive,
    printOrder,
    currentOrder,
    formatDate,
    printLabel,
    processOrder,
    processAllPlacedOrders,
    openAddOrderModal,
    openEditOrderModal,
    addNewOrder,
    updateOrder,
    deleteOrder,
    closeModal
    };
}
};
</script>

<style>
body {
background-color: #d3c6b2;
box-shadow: none;
margin: 0%;
font-family: 'Roboto', sans-serif;
}
</style>

<style scoped>
header {
padding: 20px;
background-color: #d3c6b2;
}

.logo-container {
width: 200px;
padding: 20px;
}

.logo {
margin-left: -1000px;
max-width: 200px;
height: auto;
}

h1 {
font-size: 2.5rem;
margin-bottom: 30px;
color: #000;
text-align: center;
}

.main-container {
padding: 100px;
}

.search-filter {
margin-bottom: 20px;
}

.search-input,
.status-select {
padding: 20px;
margin-right: 10px;
border: 1px solid #ccc;
border-radius: 4px;
background-color: #fff;
color: #000;
}

table {
width: 100%;
border-collapse: collapse;
color: #000;
}

th,
td {
padding: 20px;
border: 1px solid #000;
text-align: left;
}

button {
padding: 5px 10px;
background-color: #fff;
color: #000;
border: none;
border-radius: 4px;
cursor: pointer;
margin-right: 10px;
}

button:hover {
background-color: blue;
}

.modal-overlay {
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: #d3c6b2;
display: flex;
justify-content: center;
align-items: center;
}

.modal {
background-color: #d3c6b2;
padding: 20px;
border-radius: 8px;
width: 300px;
}

.form-group {
color: #000;
}

.modal h2 {
margin-bottom: 20px;
}

.form-group {
margin-bottom: 15px;
}

.form-group label {
display: block;
margin-bottom: 5px;
}

.form-group input,
.form-group select {
width: 100%;
padding: 8px;
border: 1px solid #ccc;
border-radius: 4px;
}

.modal-actions {
display: flex;
justify-content: flex-end;
gap: 10px;
}
</style>