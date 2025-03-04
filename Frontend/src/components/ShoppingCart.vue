<template>
  <div class="shopping-cart-container">
    <header>
      <div class="logo">
        <img src="/image/logo three.png" alt="DASH Shoe Logo" />
      </div>
      <nav>
        <ul>
          <li><a href="#">HOME</a></li>
          <li><a href="#">ABOUT</a></li>
          <li><a href="#">SHOPPING</a></li>
          <li><a href="#">CONTACT</a></li>
        </ul>
      </nav>
    </header>

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
              <p>Color: {{ item.color }}</p>
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
import Cookies from "js-cookie";
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
    loadCartFromCookies() {
      this.cart = JSON.parse(Cookies.get("cart") || "[]");
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
      Cookies.set("cart", JSON.stringify(this.cart), { expires: 7 });
    },
  },
  mounted() {
    this.loadCartFromCookies();
    this.logShoppingVisit();
  },
};
</script>
<style scoped>
.shopping-cart-container {
  padding: 20px;
}

header {
  margin-bottom: 20px;
}

.content {
  display: flex;
  justify-content: space-between;
  gap: 20px;
}

.cart-left {
  width: 65%;
}

.cart-items {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.cart-item {
  display: flex;
  gap: 15px;
  border: 1px solid #ddd;
  padding: 15px;
  border-radius: 8px;
  align-items: center;
}

.cart-item img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 8px;
}

.details {
  flex: 1;
}

.details h3 {
  margin: 0;
}

.details p {
  margin: 5px 0;
}

.details .price {
  font-weight: bold;
  color: #333;
}

.quantity-controls {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.quantity-controls button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}

.quantity-controls button:hover {
  background-color: #0056b3;
}

.cart-right {
  width: 30%;
  background-color: #f9f9f9;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.cart-right h2 {
  margin-top: 0;
}

.cart-right p {
  font-size: 16px;
  margin: 10px 0;
}

.checkout-button,
.back-button {
  display: block;
  width: 100%;
  padding: 10px 15px;
  margin-top: 10px;
  font-size: 16px;
  font-weight: bold;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}

.checkout-button {
  background-color: #4caf50;
  color: white;
}

.back-button {
  background-color: #f44336;
  color: white;
}

.checkout-button:hover {
  background-color: #45a049;
}

.back-button:hover {
  background-color: #e53935;
}

.empty-cart {
  text-align: center;
  font-size: 16px;
  font-weight: bold;
  color: #999;
}
</style>