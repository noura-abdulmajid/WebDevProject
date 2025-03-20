<template>
  <div class="products-container">
    <h1>Admin Products</h1>

    <!-- Loading State -->
    <div v-if="loading">
      <p>Loading products...</p>
    </div>

    <!-- Category Summary View -->
    <div v-else-if="view === 'summary'" class="category-grid">
      <!-- Total Products Card -->
      <div class="category-card" @click="showProducts('all')">
        <h2>Total Products</h2>
        <p>{{ products.length }} items</p>
      </div>

      <!-- Male Products Card -->
      <div class="category-card" @click="showProducts('male')">
        <h2>Male</h2>
        <p>{{ maleProductsCount }} items</p>
      </div>

      <!-- Female Products Card -->
      <div class="category-card" @click="showProducts('female')">
        <h2>Female</h2>
        <p>{{ femaleProductsCount }} items</p>
      </div>

      <!-- Kids Products Card -->
      <div class="category-card" @click="showProducts('kids')">
        <h2>Kids</h2>
        <p>{{ kidsProductsCount }} items</p>
      </div>

      <!-- Unisex Products Card -->
      <div class="category-card" @click="showProducts('unisex')">
        <h2>Unisex</h2>
        <p>{{ unisexProductsCount }} items</p>
      </div>
    </div>

    <!-- Product List View -->
    <div v-else-if="view === 'products'" class="products-view">
      <!-- Back Button Section -->
      <div class="back-section">
        <button @click="goBack" class="styled-back-button">
          Back to Summary
        </button>
      </div>

      <!-- Product Cards Section -->
      <div class="products-grid">
        <div v-for="product in filteredProducts" :key="product.P_ID" class="product-card">
          <strong>{{ product.p_name }}</strong>
          <p>{{ product.description }}</p>
          <p><strong>Categories:</strong> {{ product.categories.join(", ") }}</p>
          <p><strong>Colours:</strong> {{ product.colours.join(", ") }}</p>
          <p><strong>Price:</strong> ${{ product.price }}</p>
          <p><strong>Stock Status:</strong> {{ product.overall_stock_status }}</p>
          <img :src="product.photo" alt="Product Image"/>
        </div>
      </div>
    </div>

    <!-- No Products Found -->
    <div v-else>
      <p>No products found.</p>
    </div>
  </div>
</template>


<script>
import axios from "@/services/axiosClient.js";

export default {
  name: "AdminProducts",
  data() {
    return {
      products: [],
      loading: true,
      view: "summary",
      selectedCategory: null,
    };
  },
  computed: {

    maleProductsCount() {
      return this.products.filter((product) => product.gender_target === "male").length;
    },
    femaleProductsCount() {
      return this.products.filter((product) => product.gender_target === "female").length;
    },
    kidsProductsCount() {
      return this.products.filter((product) => product.gender_target === "kids").length;
    },
    unisexProductsCount() {
      return this.products.filter((product) => product.gender_target === "unisex").length;
    },

    filteredProducts() {
      if (this.selectedCategory === "all") {
        return this.products;
      }
      return this.products.filter((product) => product.gender_target === this.selectedCategory);
    },
  },
  methods: {
    fetchProducts() {
      axios
          .get("http://localhost:8000/api/DashShoe/admin/get_products")
          .then((response) => {
            console.log(response.data);
            this.products = response.data.products;
            this.loading = false;
          })
          .catch((error) => {
            console.error("Error fetching products:", error);
            this.loading = false;
          });
    },
    showProducts(category) {
      this.selectedCategory = category;
      this.view = "products";
    },
    goBack() {
      this.view = "summary";
      this.selectedCategory = null;
    },
  },
  mounted() {
    this.fetchProducts();
  },
};

</script>

<style scoped>
/* General Container Styling */
.products-container {
  max-height: 80vh;
  overflow-y: auto;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Heading Styling */
h1 {
  color: #333;
  text-align: center;
  font-size: 2rem;
  margin-bottom: 20px;
}

/* Category Grid Layout */
.category-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  padding: 10px;
}

/* Category Cards Styling */
.category-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 15px;
  text-align: center;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.category-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.category-card h2 {
  color: #333;
  font-size: 1.5rem;
  margin-bottom: 10px;
}

.category-card p {
  font-size: 1rem;
  color: #666;
}

/* Grid for Detailed Products */
.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

/* Product Cards Styling */
.product-card {
  background: #fff;
  border-radius: 10px;
  padding: 15px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.product-card img {
  max-width: 100%;
  height: auto;
  border-radius: 10px;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
  margin-top: 10px;
}

.product-card p {
  font-size: 0.9rem;
  color: #666;
  margin: 5px 0;
}

/* Container for the back button */
.back-section {
  width: 100%;
  padding: 20px 0;
  text-align: center;
  border-bottom: 1px solid #e0e0e0;
  margin-bottom: 20px;
}

.styled-back-button {
  background: #ffffff;
  color: #333;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 10px 20px;
  font-size: 1rem;
  font-weight: 500;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: background-color 0.3s, transform 0.3s;
}

.styled-back-button:hover {
  background-color: #f5f5f5;
  transform: translateY(-2px);
}

.product-card {
  background: #fff;
  border-radius: 10px;
  padding: 15px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.product-card img {
  max-width: 100%;
  height: auto;
  border-radius: 10px;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
  margin-top: 10px;
}

.product-card p {
  font-size: 0.9rem;
  color: #666;
  margin: 5px 0;
}

</style>