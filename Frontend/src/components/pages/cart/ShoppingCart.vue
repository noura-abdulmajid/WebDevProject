<template>
  <div class="shopping-cart-container">
    <div class="content">
      <div class="cart-left">
        <div v-if="cart.length === 0" class="empty-cart">Your cart is empty.</div>
        <div v-else class="cart-items">
          <div
              class="cart-item"
              v-for="(item, index) in cart"
              :key="index"
          >
            <img :src="item.image" :alt="item.name" />
            <div class="details">
              <h3>{{ item.name }}</h3>
              <p>{{ item.description }}</p>
              <div class="color-tag">
                <span
                    class="color-square"
                    :style="{ backgroundColor: item.color }">
                </span>
              </div>
              <p>Size: {{ item.size }}</p>
              <p>Quantity: {{ item.quantity }}</p>
              <p class="price">£{{ item.price.toFixed(2) }}</p>
            </div>
            <div class="quantity-controls">
              <button @click="decreaseQuantity(index)">-</button>
              <button @click="increaseQuantity(index)">+</button>
            </div>
          </div>
        </div>
      </div>

      <div class="cart-right">
        <h2>Summary</h2>
        <p>Total Items: {{ totalQuantity }}</p>
        <p>Total Price: £{{ totalPrice.toFixed(2) }}</p>
        <button class="checkout-button" @click="$router.push('/Checkout')">Checkout</button>
        <button class="back-button" @click="$router.push('/MenCollection')">Continue Shopping</button>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  name: "ShoppingCart",
  data() {
    return {
      cart: [],
    };
  },
  computed: {
    totalPrice() {
      return this.cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    },
    totalQuantity() {
      return this.cart.reduce((sum, item) => sum + item.quantity, 0);
    },
  },
  methods: {
    loadCartFromStorage() {
      this.cart = JSON.parse(localStorage.getItem("cart") || "[]");
    },
    async logShoppingVisit() {
      try {
        await axios.post(
            "http://127.0.0.1:8000/api/DashShoe/log-visit",
            {
              page: "ShoppingCart-page",
              timestamp: new Date().toISOString(),
            },
            {
              headers: {
                "Content-Type": "application/json",
              },
            }
        );
      } catch (error) {
        console.error("Failed to log user visit:", error);
      }
    },
    increaseQuantity(index) {
      this.cart[index].quantity++;
      this.updateCart();
    },
    decreaseQuantity(index) {
      if (this.cart[index].quantity > 1) {
        this.cart[index].quantity--;
      } else {
        this.cart.splice(index, 1);
      }
      this.updateCart();
    },
    updateCart() {
      localStorage.setItem("cart", JSON.stringify(this.cart));
      window.dispatchEvent(new Event("cart-updated"));
    },

  },
  mounted() {
    this.loadCartFromStorage();
    this.logShoppingVisit();
  },
};
</script>
<style scoped>
.shopping-cart-container {
  padding: 40px 20px;
  margin-top: 100px;
  font-family: 'Inter', sans-serif;

  color: #fff;
}

.content {
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.cart-left {
  flex: 1 1 60%;
  max-height: calc(100vh - 180px);
  overflow-y: auto;
}

.cart-items {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.cart-item {
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  padding: 20px;
  align-items: center;
}

.cart-item img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 10px;
  flex-shrink: 0;
}

.details {
  flex: 1;
  min-width: 200px;
}

.details h3 {
  font-size: 18px;
  color: #333;
  margin: 0 0 5px;
}

.details p {
  font-size: 14px;
  color: #555;
  margin: 4px 0;
}

.price {
  font-weight: bold;
  color: #111;
  font-size: 15px;
}

.color-tag {
  display: flex;
  align-items: center;
  margin: 5px 0;
}

.color-square {
  width: 20px;
  height: 20px;
  border-radius: 5px;
  border: 1px solid #ccc;
}

.quantity-controls {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.quantity-controls button {
  background-color: #333;
  color: #fff;
  border: none;
  padding: 6px 10px;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.2s;
}

.quantity-controls button:hover {
  background-color: #000;
}

.cart-right {
  flex: 1 1 35%;
  background-color: #fff;
  color: #333;
  border-radius: 12px;
  padding: 30px 25px;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
  height: fit-content;
}

.cart-right h2 {
  margin-top: 0;
  font-size: 22px;
  margin-bottom: 20px;
}

.cart-right p {
  font-size: 15px;
  margin: 8px 0;
}

.checkout-button,
.back-button {
  display: block;
  width: 100%;
  padding: 12px;
  font-size: 16px;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  margin-top: 14px;
  transition: background 0.3s ease;
}

.checkout-button {
  background-color: #28a745;
  color: white;
}

.checkout-button:hover {
  background-color: #218838;
}

.back-button {
  background-color: #dc3545;
  color: white;
}

.back-button:hover {
  background-color: #c82333;
}

.empty-cart {
  text-align: center;
  padding: 60px 0;
  font-size: 18px;
  font-weight: 500;
  color: #eee;
}

/* Responsive layout */
@media (max-width: 768px) {
  .content {
    flex-direction: column;
  }

  .cart-left,
  .cart-right {
    width: 100%;
  }

  .cart-item {
    flex-direction: column;
    align-items: flex-start;
  }

  .quantity-controls {
    flex-direction: row;
    gap: 10px;
  }
}



</style>