<template>
  <div>
    <header>
      <div class="logo-container">
        <img src="/logo.png" class="logo" alt="company logo" />
      </div>
    </header>
    <div class="main-container">
      <div class="faq-container">
        <div class="wrapper">
          <h1>Frequently Asked Questions</h1>
          <div class="faq-item" v-for="(faq, index) in faqs" :key="index">
            <div class="faq-question" @click="toggleAnswer(index)">
              <span>{{ faq.question }}</span>
              <span class="toggle-icon">{{ faq.isOpen ? '-' : '+' }}</span>
            </div>
            <div class="faq-answer" v-if="faq.isOpen">
              <p>{{ faq.answer }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive } from 'vue';

interface FAQ {
  question: string;
  answer: string;
  isOpen: boolean;
}

export default defineComponent({
  setup() {
    const faqs = reactive<FAQ[]>([
      {
        question: 'How to place an order?',
        answer: 'You can place an order by filling out the checkout form on our website.',
        isOpen: false,
      },
      {
        question: 'What payment methods do you accept?',
        answer: 'We accept debit cards, credit cards, PayPal, and other secure payment methods.',
        isOpen: false,
      },
      {
        question: 'How can I track my order?',
        answer: 'Once your order is shipped, you will receive a tracking number via email.',
        isOpen: false,
      },
      {
        question: 'What is your return policy?',
        answer: 'We offer a 30-day return policy for products that meet our return policy (Products must be in their orignal condition, unworn with tags attached, refunds will be processed within 5-7 business days after we receive you return and shipping costs are non-refundable). ',
        isOpen: false,
      },
      {
        question: 'How long do you take to deliver a product and which service will be used?',
        answer: 'We deliver the products within 5-7 days and we will use courier services based on feasability.',
        isOpen: false,
      }
    ]);

    const toggleAnswer = (index: number) => {
      faqs[index].isOpen = !faqs[index].isOpen;
    };

    return {
      faqs,
      toggleAnswer,
    };
  },
});
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

.faq-container {
  flex: 1;
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

.faq-item {
  margin-bottom: 20px;
  border-bottom: 1px solid #ccc;
}

.faq-question {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 0;
  cursor: pointer;
  font-size: 1.1rem;
  color: #333;
}

.faq-question:hover {
  color: #007bff;
}

.toggle-icon {
  font-size: 1.5rem;
  font-weight: bold;
}

.faq-answer {
  padding: 15px 0;
  font-size: 1rem;
  color: #666;
}

@media (max-width: 768px) {
  .main-container {
    flex-direction: column;
  }

  .wrapper {
    padding: 20px;
  }

  h1 {
    font-size: 2rem;
  }

  .faq-question {
    font-size: 1rem;
  }

  .faq-answer {
    font-size: 0.9rem;
  }
}
</style>