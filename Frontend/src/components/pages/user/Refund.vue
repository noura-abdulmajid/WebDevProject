<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-8">Refund Service</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div>
        <RefundForm @refund-submitted="handleRefundSubmitted" />
      </div>
      
      <div>
        <RefundList ref="refundList" />
      </div>
    </div>
  </div>
</template>

<script>
import RefundForm from './RefundForm.vue';
import RefundList from './RefundList.vue';

export default {
  name: 'Refund',
  components: {
    RefundForm,
    RefundList
  },
  methods: {
    handleRefundSubmitted() {
      // Refresh refund list
      this.$refs.refundList.fetchRefunds();
    }
  }
};
</script> 