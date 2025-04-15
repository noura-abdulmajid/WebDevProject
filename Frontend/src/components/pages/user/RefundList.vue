<template>
  <div class="refund-list">
    <h2 class="text-xl font-bold mb-4">Refund History</h2>
    
    <div v-if="loading" class="text-center py-4">
      Loading...
    </div>
    
    <div v-else-if="error" class="text-red-600">
      {{ error }}
    </div>
    
    <div v-else-if="refunds.length === 0" class="text-gray-500">
      No refund records found
    </div>
    
    <div v-else class="space-y-4">
      <div v-for="refund in refunds" :key="refund.id" class="border rounded-lg p-4">
        <div class="flex justify-between items-start">
          <div>
            <p class="font-medium">Order ID: {{ refund.order_id }}</p>
            <p class="text-gray-600">Amount: ${{ refund.amount }}</p>
            <p class="text-gray-600">Reason: {{ refund.reason }}</p>
            <p class="text-gray-600">Request Date: {{ formatDate(refund.created_at) }}</p>
          </div>
          <div>
            <span :class="getStatusClass(refund.status)">
              {{ getStatusText(refund.status) }}
            </span>
          </div>
        </div>
        
        <div v-if="refund.admin_notes" class="mt-2 text-sm text-gray-500">
          <p>Admin Notes: {{ refund.admin_notes }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'RefundList',
  data() {
    return {
      refunds: [],
      loading: true,
      error: null
    };
  },
  methods: {
    async fetchRefunds() {
      try {
        const response = await axios.get('/api/DashShoe/refunds');
        this.refunds = response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to load refund records. Please try again later.';
      } finally {
        this.loading = false;
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleString('en-US');
    },
    getStatusClass(status) {
      const classes = {
        pending: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800',
        completed: 'bg-blue-100 text-blue-800'
      };
      return `px-2 py-1 rounded-full text-sm ${classes[status]}`;
    },
    getStatusText(status) {
      const texts = {
        pending: 'Processing',
        approved: 'Approved',
        rejected: 'Rejected',
        completed: 'Completed'
      };
      return texts[status];
    }
  },
  created() {
    this.fetchRefunds();
  }
};
</script> 