<template>
  <div class="layout-container">
    <!-- Navbar -->
    <div class="navbar-wrapper">
    <nav :class="['navbar', { 'hidden': isHidden }]">
      <div class="container">
        <!-- Logo -->
        <div class="logo">
          <a href="/">
          <img src="@/assets/image/logo/dashshoeCrop.png" alt="DashShoe Store" class="logo-img" />
          </a>
        </div>

        <!-- Navigation Links -->
        <ul class="nav-links">
          <router-link :to="{ name: 'NewArrival' }">New</router-link>
          <router-link :to="{ name: 'Sustainability' }">Sustainability</router-link>
          <router-link :to="{ name: 'MenCollection' }">Men</router-link>
          <router-link :to="{ name: 'WomenCollection' }">Women</router-link>
          <router-link :to="{ name: 'ChildrenCollection' }">Children</router-link>
        </ul>

        <!-- Search and Icons -->
        <div class="right-section">
          <div class="search-container">
            <input type="text" v-model="searchQuery" placeholder="Search" @keyup.enter="searchProducts" />
            <button @click="searchProducts">🔍</button>
          </div>

          <div class="icons-container">
            <!-- Welcome Dropdown -->
            <div class="dropdown-wrapper" v-if="isLoggedIn">
              <div class="welcome-button" @click="toggleDropdown">
                Welcome,
                {{ userFirstName }}
              </div>
              <div v-if="showDropdown" class="dropdown-menu">
                <router-link :to="{ name: 'CustomerDashboard' }">Profile</router-link>
                <a href="#" @click.prevent="logout">Logout</a>
              </div>
            </div>

            <router-link v-if="!isLoggedIn" :to="{ name: 'user-login' }" class="icon">
              <img src="@/assets/image/icon/login.png" class="icon-img" />
            </router-link>
            <router-link :to="{ name: 'ProductFavorites' }" class="icon">
              <img src="@/assets/image/icon/favourite.png" class="icon-img" />
            </router-link>
            <router-link :to="{ name: 'ShoppingCart' }" class="icon cart-wrapper">
              <img src="@/assets/image/icon/cart.png" class="icon-img" />
              <span v-if="cartCount > 0" class="cart-count">{{ cartCount }}</span>
            </router-link>
          </div>
      </div>
      </div>
    </nav>
    </div>

    <!-- Main Content -->
    <main class="main-content">
      <router-view></router-view>
    </main>

    <!-- Bottom Navigation Bar -->
    <footer class="bottom-navbar" v-if="!$route.meta.hideFooter">
      <div class="container footer-container">
        <div class="footer-section">
          <h4>Shopping with DashShoe</h4>
          <ul>
            <li><a href="/contact">Find a Store</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4>Customer Care</h4>
          <ul>
            <li><a href="customer-dashboard/orders">Delivery & Returns</a></li>
            <li><a href="/contact">Help & Contact Us</a></li>
            <li><a href="customer-dashboard/orders">Track My Order</a></li>
            <li><a href="/site-review">Site Review</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4>Legal</h4>
          <ul>
            <li><a href="/contact">Site Map</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>Copyright © 2025 DashShoe. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<script src="../../scripts/layouts/HomepageLayoutScript.js"></script>
<style scoped src="../../styles/layouts/HomepageLayoutStyle.css"></style>
