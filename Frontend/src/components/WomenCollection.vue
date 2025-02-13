<template>
  <div id="app">
    <header>
      <div class="logo">
        <img src="/src/image/logo three.png" alt="DASH Shoe Logo"/>
      </div>
      <nav>
        <ul>
          <li class="dropdown-parent">
            <a>HOME</a>
            <ul class="dropdown">
              <li><a href="/">Home Page</a></li>
            </ul>
          </li>
          <li><a href="#">ABOUT</a></li>
          <li class="dropdown-parent">
            <a href="#">SHOPPING</a>
            <ul class="dropdown">
              <li><a href="/WomenCollection">Women's</a></li>
              <li><a href="/MenCollection">Men's</a></li>
              <li><a href="/ChildrenCollection">Children</a></li>
            </ul>
          </li>
          <li class="dropdown-parent">
            <a>CONTACT</a>
            <ul class="dropdown">
              <li><a href="/contact">Contact Us</a></li>
            </ul>
          </li>
          <li>
            <a href="/ShoppingCart"
               class="shopping-cart">
              SHOPPING CART <span v-if="cartCount > 0">({{ cartCount }})</span>
            </a>
          </li>
        </ul>
      </nav>
    </header>
    <div class="header">Women's Collection</div>
    <div class="banner container">
      <div>
        <h1>Discover our latest arrivals and top picks</h1>
        <button class="shop-button">Shop Now</button>
      </div>
      <div>
        <img src="/src/image/womens banner new.png" alt="Illustration"/>
      </div>
    </div>
    <div class="products container">
      <div
          class="product"
          v-for="(product, index) in products"
          :key="index"
      >
        <img :src="getImageURL(product.image)" :alt="product.name"/>
        <h3>{{ product.name }}</h3>
        <p class="price">{{ product.price }}</p>
        <button class="add-cart" @click="addToCart(product)">Add to Cart</button>
      </div>
    </div>
  </div>
</template>
<script>
import WomenCollectionScript from "@/scripts/WomenCollectionScript.js";

export default WomenCollectionScript;

</script>

<style src="@/styles/WomenCollection.css"></style>