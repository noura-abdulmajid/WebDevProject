<template>
<div>
    <header>
    <div class="logo-container">
        <img src="/logo.png" class="logo" alt="company logo" />
    </div>
    </header>
    <div class="main-container">
    <div class="refund-container">
        <div class="wrapper">
        <h1>Refund Requests Management</h1>
        <div class="filter-section">
            <input type="text" placeholder="Search by order ID or customer name" v-model="searchQuery" />
            <select v-model="statusFilter">
            <option value="all">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="received">Received</option>
            <option value="processed">Processed</option>
            </select>
        </div>
        
        <div class="refund-list">
            <div class="refund-header">
            <div class="header-item">Order ID</div>
            <div class="header-item">Customer</div>
            <div class="header-item">Request Date</div>
            <div class="header-item">Status</div>
            <div class="header-item">Actions</div>
            </div>
            
            <div class="refund-item" v-for="request in filteredRequests" :key="request.id">
            <div class="item-detail">{{ request.orderId }}</div>
            <div class="item-detail">{{ request.customerName }}</div>
            <div class="item-detail">{{ formatDate(request.requestDate) }}</div>
            <div class="item-detail status" :class="request.status">
                {{ request.status }}
            </div>
            <div class="item-detail actions">
                <button 
                v-if="request.status === 'pending'" 
                @click="markAsReceived(request.id)"
                class="action-button received"
                >
                Mark Received
                </button>
                <button 
                v-if="request.status === 'received' && isWithin30Days(request.requestDate)" 
                @click="processRefund(request.id)"
                class="action-button processed"
                >
                Process Refund
                </button>
                <span v-if="!isWithin30Days(request.requestDate)" class="expired">
                Return period expired
                </span>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref, computed } from 'vue'

interface RefundRequest {
id: string
orderId: string
customerName: string
requestDate: Date
status: 'pending' | 'received' | 'processed'
amount: number
}

export default defineComponent({
setup() {
    const searchQuery = ref('')
    const statusFilter = ref('all')
    
    const refundRequests = reactive<RefundRequest[]>([
    {
        id: '1',
        orderId: 'ORD-1001',
        customerName: 'Johnny',
        requestDate: new Date(2025, 2, 15),
        status: 'pending',
        amount: 49.99
    },
    {
        id: '2',
        orderId: 'ORD-1002',
        customerName: 'Tom',
        requestDate: new Date(2025, 2, 10),
        status: 'received',
        amount: 129.99
    },
    {
        id: '3',
        orderId: 'ORD-1003',
        customerName: 'Jerry',
        requestDate: new Date(2025, 1, 20),
        status: 'pending',
        amount: 79.99
    },
    {
        id: '4',
        orderId: 'ORD-1004',
        customerName: 'Clark Kent',
        requestDate: new Date(2025, 2, 5),
        status: 'processed',
        amount: 29.99
    }
    ])

    const filteredRequests = computed(() => {
    return refundRequests.filter(request => {
        const matchesSearch = 
        request.orderId.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        request.customerName.toLowerCase().includes(searchQuery.value.toLowerCase())
        
        const matchesStatus = 
        statusFilter.value === 'all' || 
        request.status === statusFilter.value
        
        return matchesSearch && matchesStatus
    })
    })

    const formatDate = (date: Date) => {
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    })
    }

    const isWithin30Days = (requestDate: Date) => {
    const today = new Date()
    const diffTime = today.getTime() - requestDate.getTime()
    const diffDays = diffTime / (1000 * 60 * 60 * 24)
    return diffDays <= 30
    }

    const markAsReceived = (id: string) => {
    const request = refundRequests.find(r => r.id === id)
    if (request) {
        request.status = 'received'
    }
    }

    const processRefund = (id: string) => {
    const request = refundRequests.find(r => r.id === id)
    if (request) {
        request.status = 'processed'
    }
    }

    return {
    searchQuery,
    statusFilter,
    refundRequests,
    filteredRequests,
    formatDate,
    isWithin30Days,
    markAsReceived,
    processRefund
    }
}
})
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
body {
background-color: #d3c6b2;
box-shadow: none;
margin: 0%;
font-family: 'Roboto', sans-serif;
}

header {
padding: 20px;
background-color: #d3c6b2;
}

.logo-container {
width: 200px;
padding: 20px;
}

.logo {
margin-left: -700px;
max-width: 200px;
height: auto;
}

.main-container {
width: 1000px;
margin: 0 auto;
padding: 20px;
display: flex;
gap: 30px;
height: auto;
}

.wrapper {
background-color: #d3c6b2;
border-radius: 12px;
padding: 40px;
}

h1 {
font-size: 1.99rem;
margin-bottom: 30px;
color: dimgrey;
text-align: center;
}

.filter-section {
display: flex;
gap: 20px;
margin-bottom: 30px;
}

.filter-section input {
flex: 2;
padding: 12px;
border: 1px solid #333;
border-radius: 8px;
font-size: 1rem;
background-color: #f0f8ff;
}

.filter-section select {
flex: 1;
padding: 12px;
border: 1px solid #333;
border-radius: 8px;
font-size: 1rem;
background-color: #000;
}

.refund-list {
color: #000;
border-radius: 8px;
overflow: hidden;
}

.refund-header {
display: flex;
background-color: #000;
color: white;
padding: 15px 10px;
font-weight: bold;
}

.header-item {
flex: 1;
text-align: center;
}

.refund-item {
display: flex;
padding: 15px 10px;
border-bottom: 1px solid #eee;
background-color: #d3c6b2;
align-items: center;
}

.refund-item:last-child {
border-bottom: none;
}

.item-detail {
flex: 1;
text-align: center;
}

.status {
text-transform: capitalize;
font-weight: bold;
}

.status.pending {
color: #ff9800;
}

.status.received {
color: #2196f3;
}

.status.processed {
color: #4caf50;
}

.actions {
display: flex;
justify-content: center;
}

.action-button {
padding: 8px 15px;
border: none;
border-radius: 4px;
cursor: pointer;
font-weight: bold;
transition: background-color 0.3s;
}

.action-button.received {
background-color: #2196f3;
color: white;
}

.action-button.received:hover {
background-color: #0b7dda;
}

.action-button.processed {
background-color: #4caf50;
color: white;
}

.action-button.processed:hover {
background-color: #388e3c;
}

.expired {
color: red;
font-style: italic;
}

@media (max-width: 768px) {
.main-container {
    width: 100%;
    padding: 10px;
}

.wrapper {
    padding: 20px;
}

.refund-header, .refund-item {
    flex-direction: column;
    align-items: flex-start;
}

.header-item, .item-detail {
    flex: none;
    width: 100%;
    text-align: left;
    margin-bottom: 5px;
}

.filter-section {
    flex-direction: column;
}
}
</style>