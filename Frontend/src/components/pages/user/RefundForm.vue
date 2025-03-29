<template>
  <div class="refund-form">
    <h2 class="text-xl font-bold mb-4">Request Refund</h2>
    <form @submit.prevent="submitRefund" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Order ID</label>
        <input
          v-model="form.order_id"
          type="text"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          required
        />
      </div>
      
      <div>
        <label class="block text-sm font-medium text-gray-700">Reason for Refund</label>
        <textarea
          v-model="form.reason"
          rows="3"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          required
        ></textarea>
      </div>

      <div class="flex justify-end">
        <button
          type="submit"
          class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          :disabled="loading"
        >
          {{ loading ? 'Submitting...' : 'Submit Refund Request' }}
        </button>
      </div>
    </form>

    <div v-if="error" class="mt-4 text-red-600">
      {{ error }}
    </div>
    
    <div v-if="success" class="mt-4 text-green-600">
      {{ success }}
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'RefundForm',
  data() {
    return {
      form: {
        order_id: '',
        reason: ''
      },
      loading: false,
      error: null,
      success: null
    };
  },
  methods: {
    async submitRefund() {
      this.loading = true;
      this.error = null;
      this.success = null;

      try {
        const response = await axios.post('/api/DashShoe/refunds', this.form);
        this.success = response.data.message;
        this.form = {
          order_id: '',
          reason: ''
        };
        this.$emit('refund-submitted');
      } catch (error) {
        this.error = error.response?.data?.message || 'Submission failed. Please try again later.';
      } finally {
        this.loading = false;
      }
    }
  }
};
</script> 