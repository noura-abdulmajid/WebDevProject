<template>
<div>
    <header>
    <div class="logo-container">
        <img src="/logo.png" class="logo" alt="company logo" />
    </div>
    </header>
    <div class="main-container">
    <div class="message-center">
        <div class="wrapper">
        <h1>Admin Message Center</h1>
        
        <div class="message-filters">
            <input 
            type="text" 
            placeholder="Search customer messages..." 
            v-model="searchQuery"
            class="search-input"
            >
            <select v-model="filterStatus" class="status-filter">
            <option value="all">All Messages</option>
            <option value="unread">Unread</option>
            <option value="urgent">Urgent</option>
            </select>
        </div>
        
        <div class="messages-container">
            <div 
            v-for="(message, index) in filteredMessages" 
            :key="index" 
            class="message-card"
            :class="{ unread: !message.read, urgent: message.urgent }"
            @click="openMessage(message)"
            >
            <div class="message-header">
                <span class="customer">{{ message.customer }}</span>
                <span class="order-id">Order #{{ message.orderId }}</span>
                <span class="time">{{ formatTime(message.time) }}</span>
            </div>
            <p class="preview">{{ message.preview }}</p>
            </div>
        </div>
        </div>
    </div>
    </div>

    <div v-if="selectedMessage" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
        <div>
            <h3>{{ selectedMessage.customer }}</h3>
            <span class="order-id">Order #{{ selectedMessage.orderId }}</span>
        </div>
        <span class="modal-time">{{ formatTime(selectedMessage.time) }}</span>
        <button class="close-btn" @click="closeModal">×</button>
        </div>
        <div class="modal-content">
        <p>{{ selectedMessage.text }}</p>
        </div>
        <div class="modal-reply">
        <textarea 
            v-model="replyText" 
            placeholder="Type your response..."
            class="reply-textarea"
        ></textarea>
        <div class="modal-actions">
            <button @click="markAsResolved" class="action-btn resolved">Mark Resolved</button>
            <button @click="sendReply" class="action-btn reply">Send Reply</button>
        </div>
        </div>
    </div>
    </div>
</div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref, computed } from 'vue'

interface Message {
id: number
customer: string
orderId: string
text: string
preview: string
time: Date
read: boolean
urgent: boolean
}

export default defineComponent({
setup() {
    const searchQuery = ref('')
    const filterStatus = ref('all')
    const selectedMessage = ref<Message | null>(null)
    const replyText = ref('')
    
    const messages = reactive<Message[]>([
    {
        id: 1,
        customer: "Johnson",
        orderId: "ORD-10045",
        text: "I haven't received my order yet, even though it was supposed to arrive 3 days ago. The tracking hasn't updated in a week.",
        preview: "Haven't received my order, tracking not updating...",
        time: new Date(2025, 2, 15, 8, 15),
        read: false,
        urgent: true
    },
    {
        id: 2,
        customer: "Harry",
        orderId: "ORD-10032",
        text: "The item I received is damaged. How do I proceed with a replacement?",
        preview: "Received damaged item, need replacement...",
        time: new Date(2025, 2, 14, 14, 30),
        read: true,
        urgent: true
    },
    {
        id: 3,
        customer: "Smith",
        orderId: "ORD-10028",
        text: "I'd like to change the shipping address for my order if it hasn't shipped yet.",
        preview: "Request to change shipping address...",
        time: new Date(2025, 2, 14, 9, 45),
        read: false,
        urgent: false
    },
    {
        id: 4,
        customer: "David",
        orderId: "ORD-10012",
        text: "Just wanted to say thank you for the excellent customer service on my last return!",
        preview: "Thank you for excellent service...",
        time: new Date(2025, 2, 13, 16, 20),
        read: true,
        urgent: false
    }
    ])

    const filteredMessages = computed(() => {
    return messages.filter(message => {
        const matchesSearch = 
        message.customer.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        message.text.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        message.orderId.toLowerCase().includes(searchQuery.value.toLowerCase())
        
        const matchesStatus = 
        filterStatus.value === 'all' || 
        (filterStatus.value === 'unread' && !message.read) ||
        (filterStatus.value === 'urgent' && message.urgent)
        
        return matchesSearch && matchesStatus
    }).sort((a, b) => b.time.getTime() - a.time.getTime())
    })

    const formatTime = (date: Date) => {
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + 
            ' • ' + 
            date.toLocaleDateString([], { month: 'short', day: 'numeric' })
    }

    const openMessage = (message: Message) => {
    selectedMessage.value = message
    message.read = true
    replyText.value = ''
    }

    const closeModal = () => {
    selectedMessage.value = null
    }

    const sendReply = () => {
    if (replyText.value.trim()) {
        const newMessage: Message = {
        id: messages.length + 1,
        customer: "Admin (You)",
        orderId: selectedMessage.value?.orderId || "ADMIN",
        text: replyText.value,
        preview: replyText.value.substring(0, 30) + (replyText.value.length > 30 ? '...' : ''),
        time: new Date(),
        read: true,
        urgent: false
        }
        messages.unshift(newMessage)
        replyText.value = ''
    }
    }

    const markAsResolved = () => {
    if (selectedMessage.value) {
        selectedMessage.value.urgent = false
        closeModal()
    }
    }

    return {
    searchQuery,
    filterStatus,
    messages,
    filteredMessages,
    selectedMessage,
    replyText,
    formatTime,
    openMessage,
    closeModal,
    sendReply,
    markAsResolved
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

.message-filters {
display: flex;
gap: 20px;
margin-bottom: 30px;
}

.search-input,
.status-filter {
flex: 1;
padding: 12px;
border: 1px solid #333;
border-radius: 8px;
font-size: 1rem;
background-color: #000;
}

.messages-container {
max-height: 600px;
overflow-y: auto;
padding-right: 10px;
}

.message-card {
background-color: white;
border-radius: 8px;
padding: 20px;
margin-bottom: 15px;
cursor: pointer;
transition: all 0.3s ease;
border-left: 4px solid transparent;
}

.message-card:hover {
transform: translateY(-2px);
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.message-card.unread {
border-left: 4px solid #007bff;
background-color: #f8f9fa;
}

.message-card.urgent {
border-left: 4px solid #dc3545;
}

.message-header {
display: flex;
justify-content: space-between;
margin-bottom: 10px;
flex-wrap: wrap;
gap: 10px;
}

.customer {
font-weight: bold;
color: #333;
flex: 1;
min-width: 150px;
}

.order-id {
font-weight: bold;
color: #6c757d;
}

.time {
font-size: 0.9rem;
color: #777;
}

.preview {
color: #555;
margin: 0;
white-space: nowrap;
overflow: hidden;
text-overflow: ellipsis;
}

.modal-overlay {
position: fixed;
top: 0;
left: 0;
right: 0;
bottom: 0;
background-color: rgba(0, 0, 0, 0.5);
display: flex;
justify-content: center;
align-items: center;
z-index: 1000;
}

.modal-container {
background-color: white;
border-radius: 12px;
width: 700px;
max-width: 90%;
max-height: 80vh;
display: flex;
flex-direction: column;
}

.modal-header {
padding: 20px;
border-bottom: 1px solid #eee;
display: flex;
justify-content: space-between;
align-items: flex-start;
flex-wrap: wrap;
gap: 10px;
}

.modal-header h3 {
margin: 0;
font-size: 1.5rem;
color: #333;
}

.order-id {
font-size: 0.9rem;
color: #6c757d;
}

.modal-time {
font-size: 0.9rem;
color: #777;
}

.close-btn {
background: none;
border: none;
font-size: 1.5rem;
cursor: pointer;
color: #777;
padding: 0 10px;
}

.close-btn:hover {
color: #333;
}

.modal-content {
padding: 20px;
flex: 1;
overflow-y: auto;
border-bottom: 1px solid #eee;
white-space: pre-wrap;
}

.modal-reply {
padding: 20px;
}

.reply-textarea {
width: 100%;
padding: 15px;
border: 1px solid #ddd;
border-radius: 8px;
min-height: 100px;
margin-bottom: 15px;
font-family: inherit;
resize: vertical;
}

.modal-actions {
display: flex;
gap: 10px;
}

.action-btn {
padding: 12px 24px;
border: none;
border-radius: 8px;
cursor: pointer;
font-size: 1rem;
transition: background-color 0.3s;
flex: 1;
}

.action-btn.resolved {
background-color: #28a745;
color: white;
}

.action-btn.resolved:hover {
background-color: #218838;
}

.action-btn.reply {
background-color: #000;
color: white;
}

.action-btn.reply:hover {
background-color: #0056b3;
}

@media (max-width: 768px) {
.main-container {
    width: 100%;
    padding: 10px;
}

.wrapper {
    padding: 20px;
}

.message-filters {
    flex-direction: column;
}

.modal-header {
    flex-direction: column;
}

.modal-actions {
    flex-direction: column;
}
}
</style>