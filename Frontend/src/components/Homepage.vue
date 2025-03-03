<template>
  <div id="app">
    <header>
      <div class="logo">
        <img src="/src/image/dashshoe.png" alt="DASH Shoe Logo" />
      </div>
      <nav>
        <ul>
          <li>
            <a>HOME</a>
            <ul class="dropdown">
              <li><a href="/">Home Page</a></li>
            </ul>
          </li>
          <li>
            <a>ABOUT</a>
            <ul class="dropdown">
              <li><a href="#">Our Mission</a></li>
            </ul>
          </li>
          <li>
            <a>SHOPPING</a>
            <ul class="dropdown">
              <!--              <li><a href="#">New Arrivals</a></li>-->
              <li><a href="/WomenCollection">Women's</a></li>
              <li><a href="/MenCollection">Men's</a></li>
              <li><a href="/ChildrenCollection">Children</a></li>
            </ul>
          </li>
          <li>
            <a>CONTACT</a>
            <ul class="dropdown">
              <li><a href="/contact">Contact Us</a></li>
            </ul>
          </li>
          <li>
            <a href="/login">LOGIN</a>
          </li>
        </ul>
      </nav>
    </header>

    <div class="banner">
      <p>
        Exclusive Offer: Get 10% off your first purchase when you sign up with
        your email!!
      </p>
    </div>

    <div class="banner-large">
      <p>Check Out Our Latest Collection for Men & Women!</p>
      <img
          src="/src/image/banner 2.png"
          alt="Collection Banner"
          style="width: 80%; max-width: 1200px; margin-top: 20px"
      />
    </div>

    <section class="promo">
      <div>
        <h2>JOIN THE DASH SHOE CLUB</h2>
        <p>EARN POINTS & GET £5 OFF REWARDS!</p>
        <a href="#" class="btn">LEARN MORE</a>
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
  </div>
</template>
<script>
import HomePageScript from "@/scripts/HomePageScript.js";

export default HomePageScript;

</script>
<style src="@/styles/HomePageStyle.css"></style>