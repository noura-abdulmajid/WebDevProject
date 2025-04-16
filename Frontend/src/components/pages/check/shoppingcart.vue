<template>
<div>
  <header>
    <div class="logo-container">
      <img src="/logo.png" class="logo" alt="company logo" />
    </div>
  </header>
  <div class="container">
    <div class="title">SHOPPING CART</div>
    <div class="icon-cart" @click="showCart = true">
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
      <button class="addCart" @click="addToCart('Nike Air Force 1 Low', 100, '/image1.png')">Add To Cart</button>
    </div>
    <div class="item">
      <img src="/image2.png" alt="adidas Originals Handball Spezial" />
      <h2>adidas Originals Handball Spezial</h2>
      <div class="price">90£</div>
      <button class="addCart" @click="addToCart('adidas Originals Handball Spezial', 90, '/image2.png')">Add To Cart</button>
    </div>
  </div>

  <div class="cart-modal" v-if="showCart">
    <div class="cart-content">
      <div class="cart-header">
        <h2>Your Cart</h2>
        <button class="close-cart" @click="showCart = false">×</button>
      </div>
      <div class="cart-body">
        <div v-if="cart.length === 0">Your cart is empty.</div>
        <div v-else>
          <div class="cart-item" v-for="(item, index) in cart" :key="index">
            <img :src="item.image" :alt="item.name" />
            <div class="item-details">
              <h3>{{ item.name }}</h3>
              <p>{{ item.price }}£</p>
              <div class="quantity-controls">
                <button @click="decreaseQuantity(index)">−</button>
                <span>{{ item.quantity }}</span>
                <button @click="increaseQuantity(index)">+</button>
              </div>
              <button class="remove-item" @click="removeFromCart(index)">Remove</button>
            </div>
          </div>
        </div>
      </div>
      <div class="cart-footer">
        <div class="summary-item">
          <span>Subtotal</span>
          <span>{{ subtotal }}£</span>
        </div>
        <div class="discount-section">
          <input type="text" placeholder="Add Discount / Promo Code" />
          <button>Apply</button>
        </div>
        <button class="checkout-button">Checkout Securely</button>
      </div>
    </div>
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
computed: {
  cartCount() {
    return this.cart.reduce((total, item) => total + item.quantity, 0);
  },
  subtotal() {
    return this.cart.reduce((total, item) => total + item.price * item.quantity, 0);
  },
},
methods: {
  addToCart(name, price, image) {
    const existingItem = this.cart.find((item) => item.name === name);
    if (existingItem) {
      existingItem.quantity += 1;
    } else {
      this.cart.push({ name, price, image, quantity: 1 });
    }
  },
  removeFromCart(index) {
    this.cart.splice(index, 1);
  },
  increaseQuantity(index) {
    this.cart[index].quantity += 1;
  },
  decreaseQuantity(index) {
    if (this.cart[index].quantity > 1) {
      this.cart[index].quantity -= 1;
    } else {
      this.removeFromCart(index);
    }
  },
},
};
</script>

<style>

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

.container {
display: flex;
justify-content: space-between;
align-items: center;
padding: 20px;
background-color:#d3c6b2;
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
width: 25px;
height: 28px;
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

.cart-header{
font-size: 18px;
margin: 10px 0;
color: #000;
}

.cart-body{
font-size: 18px;
margin: 10px 0;
color: #000;
}

.cart-footer{
font-size: 23px;
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
background-color:blue;
}

.cart-modal {
position: fixed;
top: 0;
right: 0;
width: 400px;
height: 100%;
background-color:#d3c6b2;
box-shadow: -2px 0 4px rgba(0, 0, 0, 0.1);
z-index: 1000;
display: flex;
flex-direction: column;
}

.cart-content {
flex: 1;
padding: 20px;
overflow-y: auto;
}

.cart-header {
display: flex;
justify-content: space-between;
align-items: center;
margin-bottom: 20px;
}

.cart-header h2 {
margin: 0;
}

.close-cart {
background: color #000;
border: none;
font-size: 12px;
cursor: pointer;
}

.cart-item {
display: flex;
gap: 10px;
margin-bottom: 20px;
}

.cart-item img {
width: 80px;
height: 80px;
border-radius: 8px;
}

.item-details {
flex: 1;
}

.quantity-controls {
display: flex;
align-items: center;
gap: 10px;
margin: 10px 0;
}

.quantity-controls button {
background: #000;
border: none;
padding: 5px 10px;
border-radius: 5px;
cursor: pointer;
}

.remove-item {
color: #fff;
border: none;
padding: 5px 10px;
border-radius: 5px;
cursor: pointer;
font-size: 12px;
}

.cart-footer {
padding: 20px;
border-top: 1px solid #eee;
}

.summary-item {
display: flex;
justify-content: space-between;
margin-bottom: 10px;
}

.discount-section {
display: flex;
gap: 10px;

margin-bottom: 20px;
}

.discount-section input {
flex: 1;
padding: 10px;
border: 1px solid #ccc;
border-radius: 5px;
}

.discount-section button {
background: #000;
color: #fff;
border: none;
padding: 10px 20px;
border-radius: 5px;
cursor: pointer;
}

.checkout-button {
background: #000;
color: #fff;
border: none;
padding: 15px;
width: 100%;
border-radius: 5px;
cursor: pointer;
font-size: 16px;
margin-bottom: 10px;
}

</style>