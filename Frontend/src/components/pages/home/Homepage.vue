<template>
  <div id="app">

    <div class="banner-large">
      <h1>Welcome to DASH Shoe Club</h1>
      <p>Get 10% off your first purchase when you sign up!</p>
      <p>Check Out Our Latest Collection for Men & Women!</p>

      <img
          src="/image/banner%202.png"
          alt="Collection Banner"
          style="width: 80%; max-width: 1200px; margin-top: 20px"
      />
    </div>

    <section class="promo">
      <div>
        <h2>JOIN THE DASH SHOE CLUB</h2>
        <p>EARN POINTS & GET £5 OFF REWARDS!</p>
        <a href="/register" class="btn">Register</a>
      </div>
      <div class="carousel-container">
        <div class="carousel">
          <button class="carousel-btn left" @click="prevImage">&#10094;</button>
          <img
              v-for="(image, index) in images"
              :key="index"
              :src="image.src"
              :alt="image.alt"
              :class="{ active: index === carouselIndex }"
          />
          <button class="carousel-btn right" @click="nextImage">&#10095;</button>
        </div>
        <p class="trainer-description">
          Stylish and comfortable trainers for every occasion.
        </p>
      </div>
    </section>

    <!-- Categories Section -->
    <section class="categories">
      <h2>Shop by Category</h2>
      <div class="category-grid">
        <div class="category-card">
          <img src="/image/asics%20mens.jpeg" alt="New Arrivals">
          <h3>New Arrivals</h3>
          <p>Check out the freshest styles, just landed in store.</p>
          <a href="#" class="btn">Shop New Arrivals</a>
        </div>

        <div class="category-card">
          <img src="/image/cream heels.jpeg" alt="Women">
          <h3>Women</h3>
          <p>Explore heels, flats, trainers, and everything in between.</p>
          <a href="/WomenCollection" class="btn">Shop Women’s</a>
        </div>

        <div class="category-card">
          <img src="/image/mens%20brown%20loafers.jpeg" alt="Men">
          <h3>Men</h3>
          <p>Discover the latest men's sneakers, boots, and formal footwear.</p>
          <a href="/MenCollection" class="btn">Shop Men’s</a>
        </div>

        <div class="category-card">
          <img src="/image/kidsShoes.jpg" alt="Kids">
          <h3>Kids</h3>
          <p>Stylish and practical footwear for the younger ones.</p>
          <a href="/ChildrenCollection" class="btn">Shop Kids</a>
        </div>
      </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews">
      <h2>What Our Customers Say</h2>
      <div class="reviews-grid">
        <div class="review-card" v-for="(review, index) in reviews" :key="index">
          <p>"{{ review.text }}"</p>
          <div class="reviewer">- {{ review.author }}</div>
        </div>
      </div>
    </section>



  </div>
</template>
<script>
import HomePageScript from "@/scripts/pages/home/HomePageScript.js";

export default HomePageScript;

</script>
<style src="@/styles/pages/home/HomePageStyle.css"></style>