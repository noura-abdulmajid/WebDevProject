<template>
  <div id="dashShoeClub">
    <!-- Header -->
    <header>
      <div class="logo">
        <img src="https://via.placeholder.com/150?text=Logo" alt="DASH Shoe Club Logo">
      </div>
      <nav>
        <ul>
          <li class="dropdown-parent">
            <a href="#">HOME</a>
            <ul class="dropdown">
              <li><a href="#">Home Page</a></li>
            </ul>
          </li>
          <li class="dropdown-parent">
            <a href="#">ABOUT</a>
            <ul class="dropdown">
              <li><a href="#">Our Mission</a></li>
            </ul>
          </li>
          <li><a href="/WomenCollection">Women's</a></li>
<!--          <li class="dropdown-parent">-->
<!--            <a href="#">SHOPPING</a>-->
<!--            <ul class="dropdown">-->
<!--              <li><a href="#">New Arrivals</a></li>-->
<!--              -->
<!--              <li><a href="#">Men's</a></li>-->
<!--              <li><a href="#">Children</a></li>-->
<!--            </ul>-->
<!--          </li>-->
          <li class="dropdown-parent">
            <a href="#">CONTACT</a>
            <ul class="dropdown">
              <li><a href="#">Contact Us</a></li>
            </ul>
          </li>
        </ul>
      </nav>
      <div class="search-bar">
        <input type="text" v-model="searchTerm" placeholder="Search for shoes...">
        <button>Search</button>
      </div>
    </header>

    <!-- Collage Banner Section -->
    <section class="collage-banner">
      <h1>Welcome to DASH Shoe Club</h1>
      <p>Exclusive Offer: Get 10% off your first purchase when you sign up!</p>
      <p>Check Out Our Latest Collection for Men & Women!</p>
      <div class="banner-images">
        <img src="https://via.placeholder.com/250?text=Banner+1" alt="Banner 1">
        <img src="https://via.placeholder.com/250?text=Banner+2" alt="Banner 2">
        <img src="https://via.placeholder.com/250?text=Banner+3" alt="Banner 3">
      </div>
    </section>

    <!-- Promo Section with Carousel -->
    <section class="promo">
      <div class="promo-text">
        <h2>JOIN THE DASH SHOE CLUB</h2>
        <p>EARN POINTS & GET £5 OFF REWARDS!</p>
        <a href="#" class="btn">LEARN MORE</a>
      </div>
      <div class="carousel-container">
        <div class="carousel">
          <button class="carousel-btn left" @click="prevImage">&#10094;</button>
          <img
              v-for="(image, index) in carouselImages"
              :key="index"
              :src="image"
              :class="{ active: index === carouselIndex }"
              :alt="`Shoe ${index + 1}`">
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
          <img src="https://via.placeholder.com/300?text=Women" alt="Women">
          <h3>Women</h3>
          <p>Explore heels, flats, trainers, and everything in between.</p>
          <a href="#" class="btn">Shop Women’s</a>
        </div>
        <div class="category-card">
          <img src="https://via.placeholder.com/300?text=New+Arrivals" alt="New Arrivals">
          <h3>New Arrivals</h3>
          <p>Check out the freshest styles, just landed in store.</p>
          <a href="#" class="btn">Shop New Arrivals</a>
        </div>
        <div class="category-card">
          <img src="https://via.placeholder.com/300?text=Men" alt="Men">
          <h3>Men</h3>
          <p>Discover the latest men's sneakers, boots, and formal footwear.</p>
          <a href="#" class="btn">Shop Men’s</a>
        </div>
        <div class="category-card">
          <img src="https://via.placeholder.com/300?text=Kids" alt="Kids">
          <h3>Kids</h3>
          <p>Stylish and practical footwear for the younger ones.</p>
          <a href="#" class="btn">Shop Kids</a>
        </div>
      </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products">
      <h2>Best Sellers</h2>
      <div class="products-grid">
        <div class="product-card" v-for="(product, index) in featuredProducts" :key="index">
          <img :src="product.image" :alt="product.name">
          <h3>{{ product.name }}</h3>
          <p>£{{ product.price.toFixed(2) }}</p>
          <button class="add-to-cart-btn" @click="addToCart(product)">Add to Cart</button>
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

    <!-- Newsletter Section -->
    <section class="newsletter">
      <h2>Stay in the Loop</h2>
      <p>Sign up for our newsletter to get the latest news and exclusive offers.</p>
      <form @submit.prevent="subscribeNewsletter">
        <input type="email" placeholder="Enter your email" v-model="newsletterEmail" required>
        <button type="submit">Subscribe</button>
      </form>
    </section>

    <!-- Footer -->
    <footer>
      <p>&copy; 2025 DASH Shoe Club. All rights reserved.</p>
    </footer>
  </div>
</template>

<script>
export default {
  name: "DashShoeClub",
  data() {
    return {
      // Carousel data
      carouselImages: [
        "https://via.placeholder.com/500x300?text=Carousel+1",
        "https://via.placeholder.com/500x300?text=Carousel+2",
        "https://via.placeholder.com/500x300?text=Carousel+3"
      ],
      carouselIndex: 0,
      // Featured products data
      featuredProducts: [
        {
          name: "White Classic Sneaker",
          price: 59.99,
          image: "https://via.placeholder.com/220?text=Product+1"
        },
        {
          name: "Running Pro Trainer",
          price: 69.99,
          image: "https://via.placeholder.com/220?text=Product+2"
        },
        {
          name: "High-Top Canvas",
          price: 49.99,
          image: "https://via.placeholder.com/220?text=Product+3"
        },
        {
          name: "AirFlow Runner",
          price: 79.99,
          image: "https://via.placeholder.com/220?text=Product+4"
        }
      ],
      // Reviews data
      reviews: [
        {
          text: "These trainers are a game-changer! So comfortable and stylish.",
          author: "Alex, London"
        },
        {
          text: "Absolutely in love with the stylish design and perfect fit!",
          author: "Sarah, Manchester"
        },
        {
          text: "Great quality and quick delivery. I wear them every day now.",
          author: "Martin, Liverpool"
        }
      ],
      // Newsletter data
      newsletterEmail: ""
    };
  },
  methods: {
    // Carousel methods
    nextImage() {
      this.carouselIndex = (this.carouselIndex + 1) % this.carouselImages.length;
    },
    prevImage() {
      this.carouselIndex = (this.carouselIndex - 1 + this.carouselImages.length) % this.carouselImages.length;
    },
    // Add to cart method (placeholder)
    addToCart(product) {
      alert(`Added "${product.name}" to the cart!`);
    },
    // Newsletter subscription method (placeholder)
    subscribeNewsletter() {
      if (this.newsletterEmail) {
        alert(`Thank you for subscribing with ${this.newsletterEmail}!`);
        this.newsletterEmail = "";
      }
    }
  },
  mounted() {
    // Automatic carousel slide every 3 seconds
    setInterval(this.nextImage, 3000);
  }
};
</script>

<style scoped>
/* Global resets and typography */
* {
  box-sizing: border-box;
}
body {
  margin: 0;
  padding: 0;
}
/* Header & Navigation */
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  background-color: #8b8276;
}
.logo img {
  width: 150px;
  mix-blend-mode: multiply;
}
nav ul {
  list-style: none;
  display: flex;
  gap: 30px;
  margin: 0;
  padding: 0;
}
nav a {
  text-decoration: none;
  color: white;
  font-size: 18px;
  padding: 10px;
  display: block;
}
.dropdown {
  display: none;
  position: absolute;
  background-color: #8b8276;
  list-style: none;
  top: 100%;
  left: 0;
  min-width: 150px;
  padding: 0;
  margin: 0;
  border: 1px solid #ccc;
  z-index: 1000;
}
.dropdown li {
  width: 100%;
}
.dropdown a {
  padding: 10px;
  display: block;
  color: black;
}
nav ul li:hover .dropdown {
  display: block;
}
/* Shopping Cart Link */
.shopping-cart {
  font-weight: bold;
}
/* Search Bar */
.search-bar {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-right: 20px;
}
.search-bar input {
  padding: 8px;
  width: 200px;
  border: 1px solid #ffffffd8;
  border-radius: 10px;
}
.search-bar button {
  padding: 8px 12px;
  background: #4d382d;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
}
/* Collage Banner */
.collage-banner {
  width: 100%;
  background-color: #ffffff;
  text-align: center;
  padding: 40px 5%;
  color: black;
}
.collage-banner h1 {
  margin-bottom: 10px;
  font-size: 32px;
}
.collage-banner p {
  font-size: 18px;
  margin: 5px 0;
}
.banner-images {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  justify-content: center;
  margin-top: 20px;
}
.banner-images img {
  max-width: 250px;
  border-radius: 4px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
/* Promo Section */
.promo {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  padding: 50px 5%;
  color: white;
  position: relative;
  background-color: #8b8276;
}
.promo-text {
  flex: 1;
  margin-bottom: 20px;
  min-width: 280px;
}
.promo-text h2 {
  font-size: 42px;
  font-weight: bold;
  line-height: 1.2;
  margin-bottom: 20px;
}
.promo-text p {
  font-size: 22px;
  margin-bottom: 20px;
}
.carousel-container {
  flex: 1;
  position: relative;
  max-width: 500px;
  margin: 20px auto;
}
.carousel {
  position: relative;
  overflow: hidden;
  border: 3px solid #d3c6b2;
  background-color: white;
  border-radius: 5px;
}
.carousel img {
  max-height: 300px;
  width: auto;
  display: none;
  margin: 0 auto;
}
.carousel img.active {
  display: block;
}
.carousel-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background-color: #8b8276;
  color: white;
  border: none;
  padding: 10px;
  cursor: pointer;
  font-size: 18px;
  border-radius: 5px;
}
.carousel-btn.left { left: 10px; }
.carousel-btn.right { right: 10px; }
.trainer-description {
  margin-top: 10px;
  font-size: 18px;
  color: black;
  text-align: center;
  background-color: white;
  padding: 10px;
  border: 3px solid #d3c6b2;
  border-top: none;
  border-radius: 0 0 5px 5px;
}
/* Categories Section */
.categories {
  background-color: #fafafa;
  padding: 40px 5%;
  text-align: center;
}
.categories h2 {
  font-size: 32px;
  margin-bottom: 30px;
}
.category-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
  justify-items: center;
}
.category-card {
  background-color: #fff;
  border-radius: 5px;
  padding: 20px;
  width: 100%;
  max-width: 300px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  text-align: center;
}
.category-card img {
  width: 100%;
  border-radius: 3px;
  margin-bottom: 15px;
}
.category-card h3 {
  margin: 10px 0;
  font-size: 20px;
}
.category-card p {
  font-size: 16px;
  margin-bottom: 15px;
}
/* Featured Products Section */
.featured-products {
  background-color: #fafafa;
  padding: 50px 5%;
  text-align: center;
}
.featured-products h2 {
  font-size: 36px;
  margin-bottom: 30px;
}
.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 30px;
}
.product-card {
  background-color: #fff;
  border-radius: 5px;
  padding: 20px;
  transition: transform 0.2s;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.product-card:hover {
  transform: translateY(-5px);
}
.product-card img {
  margin-bottom: 15px;
  width: 100%;
}
.product-card h3 {
  font-size: 18px;
  margin-bottom: 10px;
}
.product-card p {
  font-size: 16px;
  margin: 0;
}
/* Add to Cart Button */
.add-to-cart-btn {
  background-color: #8b8276;
  color: black;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  border-radius: 3px;
  margin-top: 10px;
  font-size: 16px;
}
/* Reviews Section */
.reviews {
  background-color: #f4f4f4;
  padding: 40px 5%;
  text-align: center;
}
.reviews h2 {
  font-size: 32px;
  margin-bottom: 20px;
}
.reviews-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
  margin-top: 30px;
  max-width: 900px;
  margin-left: auto;
  margin-right: auto;
}
.review-card {
  background: #fff;
  border-radius: 5px;
  padding: 20px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.review-card p {
  margin-bottom: 10px;
  font-size: 16px;
  line-height: 1.4;
}
.reviewer {
  color: #333;
  font-style: italic;
}
/* Newsletter Section */
.newsletter {
  background-color: #8b8276;
  color: #fff;
  text-align: center;
  padding: 40px 5%;
}
.newsletter h2 {
  font-size: 32px;
}
.newsletter p {
  font-size: 18px;
  margin: 15px 0;
}
.newsletter form {
  margin-top: 20px;
}
.newsletter input[type="email"] {
  padding: 10px;
  width: 250px;
  border-radius: 3px;
  border: none;
  margin-right: 10px;
  font-size: 16px;
}
.newsletter button {
  background-color: #8b8276;
  padding: 10px 20px;
  color: black;
  cursor: pointer;
  border: none;
  border-radius: 3px;
  font-size: 16px;
}
/* Footer */
footer {
  background-color: #8b8276;
  color: white;
  text-align: center;
  padding: 20px;
  margin-top: auto;
}
</style>


