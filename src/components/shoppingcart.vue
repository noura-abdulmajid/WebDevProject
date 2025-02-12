<template>
  <div>
    <header>
      <div class="logo-container">
        <img src="/logo.png" class="logo" alt="company logo" />
      </div>
    </header>
    <div class="container">
      <div class="title">SHOPPING CART</div>
      <div class="icon-cart" @click="showCart=true">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312"
          />
        </svg>
        <span>{{ cartCount }}</span>
      </div>
    </div>
    <div class="listProduct">
      <div class="item">
        <img src="/image1.png" alt="Nike Air Force 1 Low" />
        <h2>Nike Air Force 1 Low</h2>
        <div class="price">100£</div>
        <button class="addCart" @click="addToCart('adidas Originals Handball Spezial', 90, '/image2.png')"> Add To Cart      
        </button>
      </div>
      <div class="item">
        <img src="/image2.png" alt="adidas Originals Handball Spezial" />
        <h2>adidas Originals Handball Spezial</h2>
        <div class="price">90£</div>
        <button class="addCart" @click="addToCart('adidas Originals Handball Spezial', 90, '/image2.png')"> Add To Cart
        </button>
      </div>
    </div>
  </div>

  <div class="cart-modal" v-if="showCart">
    <div class="cart-products">
        <h2>Your Cart</h2>
        <div v-if="cart.length === 0">Your cart is empty.</div>
        <div v-else>
          <div class="cart-item" v-for="(item, index) in cart" :key="index">
            <img :src="item.image" :alt="item.name" />
            <div>
              <h3>{{ item.name }}</h3>
              <p>{{ item.price }}£</p>
            </div>
          </div>
        </div>
        <button class="close-cart" @click="showCart = false">Close</button>
      </div>

      <div class="checkout-panel">
        <h3>Order Summary</h3>
        <div class="summary-item">
          <span>Subtotal</span>
          <span>{{ subtotal }}£</span>
        </div>
        <div class="summary-item">
          <span>Total (Excluding Delivery)</span>
          <span>{{ subtotal }}£</span>
        </div>
        <button class="checkout-button">Checkout Securely</button>
        <div class="payment-options">
          <p>Or</p>
          <button class="payment-button paypal">PayPal Express</button>
          <button class="payment-button clearpay">Clearpay Express</button>
          <button class="payment-button paypal-later">PayPal Pay Later</button>
        </div>
        <div class="discount-section">
          <input type="text" placeholder="Add Discount / Promo Code" />
          <input type="text" placeholder="Add Gift Card" />
        </div>
        <p class="privacy-policy">
          We will use your information in accordance with our
          <a href="#">Privacy Policy</a>. Updated January 2023.
        </p>
      </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      showCart: false,
      cart: [],
    };
  },
  computed:{
    cartCount(){
        return this.cart.length;
    },
    subtotal() {
      return this.cart.reduce((total, item) => total + item.price, 0); 
    },
  },
  methods: {
    addToCart(name, price, image) {
      this.cart.push({ name, price, image });
    },
    removeFromCart(index) {
      this.cart.splice(index, 1); 
    },
  },
};   
</script>

<style>
  body {
    background-color: #d4cec5;
    box-shadow: none;
    margin: 0;
    font-family: 'Roboto', sans-serif;
  }

.logo-container {
  width: 200px;
  margin: 0 px;
}

.logo {
  margin-left: -500px;
  max-width: 100%;
  height: auto;
}

.container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
}

.title {
    font-size: 24px;
    font-weight: bold;
}


.icon-cart {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    color: #000;
}

.icon-cart svg {
    width: 24px;
    height: 24px;
}

.listProduct {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 20px;
}

.item {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
    width: calc(33.33% - 40px);
}

.item img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

.item h2 {
    font-size: 18px;
    margin: 10px 0;
    color: #000;
}

.price {
    font-size: 16px;
    color: #333;
    margin: 10px 0;
}

.addCart {
    background-color: #000;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.addCart:hover {
    background-color: #333;
}

.cart-modal{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.cart-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 300px;
  max-width: 90%;
}

.cart-content h2 {
  margin-top: 0;
}

.cart-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.cart-item img {
  width: 50px;
  height: 50px;
  border-radius: 8px;
}

.remove-item {
  background-color: #ff4d4d;
  color: #fff;
  border: none;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 12px;
}

.remove-item:hover {
  background-color: #ff1a1a;
}

.close-cart {
  background-color: #000;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  margin-top: 20px;
}

.close-cart:hover {
  background-color: #333;
}

.checkout-panel {
  position: fixed;
  top:20px;
  right:20px;
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 300px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.checkout-panel h3 {
  margin-top: 0;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}

.checkout-button {
  background-color: #000;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  width: 100%;
  margin-bottom: 10px;
}

.checkout-button:hover {
  background-color: #333;
}

.payment-options {
    color: #000;
  text-align: center;
}

.payment-button {
  background-color: #fff;
  border: 1px solid #000;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  width: 100%;
  margin-bottom: 10px;
}

.payment-button.paypal {
  background-color: #003087;
  color: #fff;
}

.payment-button.clearpay {
  background-color: #00a2e8;
  color: #fff;
}

.payment-button.paypal-later {
  background-color: #ffc439;
  color: #000;
}

.discount-section input {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.privacy-policy {
  font-size: 12px;
  color: #666;
  text-align: center;
}

.privacy-policy a {
  color: #000;
  text-decoration: underline;
}
</style>