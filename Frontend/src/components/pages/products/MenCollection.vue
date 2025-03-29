<template>
  <div id="MensWear">
    <div class="header">Men's Collection</div>

    <div class="banner container">
      <div>
        <h1>Discover our latest arrivals and top picks</h1>
        <button class="shop-button">Shop Now</button>
      </div>
      <div>
        <img src="/image/mens banner.png" alt="Illustration"/>
      </div>
    </div>

    <div class="filter-container">
      <button @click="showDropdown = !showDropdown">Filter Options</button>
      <div v-if="showDropdown" class="filter-dropdown">
        <label>
          <select v-model="selectedFilter">
            <option value="">Select Filter</option>
            <option value="category">Category</option>
            <option value="color">Color</option>
          </select>
        </label>
        <label v-if="selectedFilter === 'category'">
          <select v-model="selectedCategory">
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat" :value="cat">
              {{ cat }}
            </option>
          </select>
        </label>
        <label v-if="selectedFilter === 'color'">
          <select v-model="selectedColor">
            <option value="">All Colors</option>
            <option v-for="col in colors" :key="col" :value="col">
              {{ col }}
            </option>
          </select>
        </label>
      </div>
    </div>

    <div class="sort-container">
      <label for="sort-select">Sort By:</label>
      <select id="sort-select" v-model="selectedSort">
        <option value="recommended">Recommended</option>
        <option value="priceDesc">Price (High to Low)</option>
        <option value="priceAsc">Price (Low to High)</option>
        <option value="whatsNew">What's New</option>
      </select>
    </div>

    <div class="products container">
      <div
          class="product"
          v-for="prod in sortedProducts"
          :key="prod.name"
          @click="openProduct(prod)"
      >
        <img :src="getImageURL(prod.image)" :alt="prod.name"/>
        <h3>{{ prod.name }}</h3>
        <p class="price">£{{ prod.price }}</p>

        <div class="color-tag">
          <span
              v-for="(color, index) in prod.colors"
              :key="index"
              class="color-square"
              :style="{ backgroundColor: color }"
          ></span>
        </div>

        <select v-model="prod.selectedSize" class="size-select">
          <option value="">Select UK Size</option>
          <option v-for="size in prod.sizes" :key="size" :value="size">
            {{ size }}
          </option>
        </select>
        <p>Quantity :</p>
        <select v-model="prod.selectedQuantity" class="quantity-select">
          <option v-for="n in 10" :key="n" :value="n">{{ n }}</option>
        </select>

        <button class="add-cart" @click.stop="handleAddToCart(prod)">Add to Cart</button>

        <button class="favourite-button" @click.stop="toggleFavourite(prod)">
          {{ prod.isFavourite ? '♥' : '♡' }}
        </button>
      </div>
    </div>

    <div class="modal-overlay" v-if="selectedProduct" @click.self="closeModal">
      <div class="modal-content">
        <button class="close-modal" @click="closeModal">Close</button>
        <h2>{{ selectedProduct.name }}</h2>
        <img
            :src="getImageURL(selectedProduct.image)"
            alt="Detail Image"
            class="modal-image"
        />
        <p class="modal-price">£{{ selectedProduct.price }}</p>
        <div class="color-tag">
          <span
              v-for="(color, index) in selectedProduct.colors"
              :key="index"
              class="color-square"
              :style="{ backgroundColor: color }"
              :class="{ selected: modalSelectedColor === color }"
              @click.stop="modalSelectedColor = color"
          ></span>
        </div>

        <p>{{ selectedProduct.description }}</p>
        <select v-model="modalSelectedSize" class="size-select" style="margin-top: 10px">
          <option value="">Select UK Size</option>
          <option v-for="size in selectedProduct.sizes" :key="size" :value="size">
            {{ size }}
          </option>
        </select>
        <p>Quantity :</p>
        <select v-model="modalSelectedQuantity" class="quantity-select" style="margin-top: 10px">
          <option v-for="n in 10" :key="n" :value="n">{{ n }}</option>
        </select>

        <button class="add-cart" style="margin-top: 15px" @click="modalAddToCart">
          Add to Cart
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import axiosClient from '@/services/axiosClient';
import apiConfig from '@/config/apiURL';

export default {
  name: 'MensWear',
  data() {
    return {
      cartCount: 0,
      showDropdown: false,
      selectedFilter: '',
      selectedCategory: '',
      selectedColor: '',
      selectedSort: 'recommended',
      searchTerm: '',
      selectedProduct: null,
      selectedSize: '',
      modalSelectedSize: '',
      modalSelectedColor: '',
      modalSelectedQuantity: 1,
      favourites: [],
      categories: ['Sneakers', 'Boots', 'Sandals'],
      colors: ['Red', 'Blue', 'Green', 'Black'],
      products: []
    };
  },
  computed: {
    filteredProducts() {
      console.log('Computing filtered products from:', this.products);
      return this.products.filter(prod => {
        // First check if product is valid
        if (!prod || !prod.name) {
          console.warn('Invalid product in filter:', prod);
          return false;
        }

        // Apply category filter
        if (this.selectedCategory && prod.category !== this.selectedCategory) {
          return false;
        }

        // Apply color filter
        if (this.selectedColor && (!prod.colors || !prod.colors[0] || prod.colors[0] !== this.selectedColor)) {
          return false;
        }

        // Apply search filter
        if (this.searchTerm) {
          const search = this.searchTerm.toLowerCase();
          return (
            prod.name.toLowerCase().includes(search) ||
            (prod.description && prod.description.toLowerCase().includes(search)) ||
            (prod.colors && prod.colors[0] && prod.colors[0].toLowerCase().includes(search))
          );
        }

        // Gender filter is already applied in fetchProducts
        return true;
      });
    },
    sortedProducts() {
      console.log('Computing sorted products from:', this.filteredProducts);
      let sorted = [...this.filteredProducts];
      
      if (this.selectedSort === 'priceDesc') {
        sorted.sort((a, b) => b.price - a.price);
      } else if (this.selectedSort === 'priceAsc') {
        sorted.sort((a, b) => a.price - b.price);
      } else if (this.selectedSort === 'whatsNew') {
        sorted.sort((a, b) => new Date(b.dateAdded) - new Date(a.dateAdded));
      }
      
      console.log('Sorted products result:', sorted);
      return sorted;
    }
  },
  methods: {
    safeParseArray(input) {
      if (Array.isArray(input)) {
        return input;
      }
      try {
        return JSON.parse(input);
      } catch (e) {
        console.warn("Failed to parse JSON string:", input, e);
        return [];
      }
    },
    async logMenVisit() {
      try {
        await axios.post(
            "http://127.0.0.1:8000/api/DashShoe/log-visit",
            {
              page: "MenCollection-page",
              timestamp: new Date().toISOString(),
            },
            {
              headers: {
                "Content-Type": "application/json",
              }
            }
        );
      } catch (error) {
        console.error(error);
      }
    },
    getImageURL(path) {
      return path;
    },
    openProduct(prod) {
      this.selectedProduct = prod;
      if (!this.selectedProduct.selectedQuantity) {
        this.selectedProduct.selectedQuantity = 1;
      }
      this.modalSelectedQuantity = 1;
    },
    closeModal() {
      this.selectedProduct = null;
    },
    handleAddToCart(product) {
      if (!product) {
        console.error('Invalid product data');
        return;
      }
      
      if (!product.selectedSize) {
        alert("Please select a size before adding to cart.");
        return;
      }

      const quantity = product.selectedQuantity || 1;
      if (quantity < 1) {
        console.error('Invalid quantity');
        return;
      }

      this.addToCart(product, quantity);
      if (confirm("Item added successfully.\nClick OK to continue shopping or Cancel to view your cart.")) {
      } else {
        this.$router.push("/ShoppingCart");
      }
    },
    modalAddToCart() {
      if (!this.selectedProduct) {
        console.error('No product selected');
        return;
      }

      if (!this.modalSelectedSize) {
        alert("Please select a size before adding to cart.");
        return;
      }

      if (!this.modalSelectedColor && this.selectedProduct.colors && this.selectedProduct.colors.length > 0) {
        this.modalSelectedColor = this.selectedProduct.colors[0];
      }

      const quantity = this.modalSelectedQuantity || 1;
      if (quantity < 1) {
        console.error('Invalid quantity');
        return;
      }

      this.selectedProduct.selectedSize = this.modalSelectedSize;
      this.selectedProduct.selectedColor = this.modalSelectedColor;
      this.addToCart(this.selectedProduct, quantity);
      this.closeModal();
      this.modalSelectedSize = '';
      this.modalSelectedColor = '';
      this.modalSelectedQuantity = 1;
      
      if (confirm("Item added successfully.\nClick OK to continue shopping or Cancel to view your cart.")) {
      } else {
        this.$router.push("/ShoppingCart");
      }
    },
    addToCart(product, quantity) {
      if (!product || !product.name) {
        console.error('Invalid product data');
        return;
      }

      let cart = JSON.parse(localStorage.getItem('cart') || '[]');
      const selectedColor = product.selectedColor || 
        (product.colors && product.colors.length > 0 ? product.colors[0] : '');
      
      const existingProduct = cart.find(
        item => item.name === product.name && 
               item.size === product.selectedSize &&
               item.color === selectedColor
      );

      if (existingProduct) {
        existingProduct.quantity += quantity;
      } else {
        cart.push({
          name: product.name,
          image: product.image || '',
          price: product.price || 0,
          description: product.description || '',
          color: selectedColor,
          size: product.selectedSize || '',
          quantity: quantity,
        });
      }

      localStorage.setItem('cart', JSON.stringify(cart));
      window.dispatchEvent(new Event("cart-updated"));
      this.cartCount = cart.reduce((total, item) => total + (item.quantity || 0), 0);
    },
    updateCartCountFromStorage() {
      try {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        this.cartCount = cart.reduce((total, item) => total + (item.quantity || 0), 0);
      } catch (error) {
        console.error('Error updating cart count:', error);
        this.cartCount = 0;
      }
    },
    async toggleFavourite(product) {
      if (!product || !product.P_ID) {
        console.error('Invalid product data');
        return;
      }

      try {
        const isLoggedIn = localStorage.getItem('token');
        
        if (isLoggedIn) {
          if (product.isFavourite) {
            await axiosClient.delete(apiConfig.userProfile.favorites + '/' + product.P_ID);
            product.isFavourite = false;
          } else {
            await axiosClient.post(apiConfig.userProfile.favorites + '/' + product.P_ID);
            product.isFavourite = true;
          }
        } else {
          let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
          if (product.isFavourite) {
            favorites = favorites.filter(fav => fav.P_ID !== product.P_ID);
            product.isFavourite = false;
          } else {
            favorites.push({
              P_ID: product.P_ID,
              name: product.name || '',
              image: product.image || '',
              price: product.price || 0,
              description: product.description || '',
              sizes: product.sizes || [],
              colors: product.colors || []
            });
            product.isFavourite = true;
          }
          localStorage.setItem('favorites', JSON.stringify(favorites));
        }
      } catch (error) {
        console.error('Error toggling favorite:', error);
        alert('Failed to update favorites. Please try again later.');
      }
    },
    async loadFavourites() {
      try {
        const token = localStorage.getItem('token');
        
        if (token) {
          try {
            const response = await axiosClient.get(apiConfig.userProfile.favorites, {
              headers: {
                'Authorization': `Bearer ${token}`
              }
            });
            
            if (response?.data?.favorites) {
              this.favourites = response.data.favorites;
              this.products.forEach(product => {
                if (product && product.P_ID) {
                  product.isFavourite = this.favourites.some(fav => fav.P_ID === product.P_ID);
                }
              });
            }
          } catch (error) {
            console.error('Error loading favourites from backend:', error);
            this.loadFavouritesFromStorage();
          }
        } else {
          this.loadFavouritesFromStorage();
        }
      } catch (error) {
        console.error('Error in loadFavourites:', error);
        this.loadFavouritesFromStorage();
      }
    },
    loadFavouritesFromStorage() {
      try {
        const savedFavourites = localStorage.getItem('favourites');
        if (savedFavourites) {
          this.favourites = JSON.parse(savedFavourites);
          this.products.forEach(product => {
            if (product && product.P_ID) {
              product.isFavourite = this.favourites.some(fav => fav.P_ID === product.P_ID);
            }
          });
        }
      } catch (error) {
        console.error('Error loading favourites from storage:', error);
        this.favourites = [];
      }
    },
    async fetchProducts() {
      try {
        console.log('Fetching products...');
        const response = await axiosClient.get(apiConfig.products.getAll);
        
        console.log('Raw response:', response);
        
        if (!response || !response.data) {
          console.error('Invalid response from server');
          return;
        }

        // Process products directly from response.data
        this.products = response.data
          .filter(product => product.gender_target === 'male' || product.gender_target === 'unisex')
          .map(product => ({
            P_ID: product.P_ID,
            name: product.p_name,
            image: product.photo,
            price: parseFloat(product.price),
            description: product.description,
            sizes: this.safeParseArray(product.sizes),
            colors: this.safeParseArray(product.colours),
            category: this.safeParseArray(product.categories),
            isFavourite: false,
            selectedSize: '',
            selectedQuantity: 1,
            dateAdded: product.created_at,
            gender_target: product.gender_target
          }));
        
        console.log('Processed products:', this.products);
        
        // Update computed properties
        this.$nextTick(() => {
          console.log('Filtered products:', this.filteredProducts);
          console.log('Sorted products:', this.sortedProducts);
        });
        
        await this.loadFavourites();
      } catch (error) {
        console.error("Failed to fetch products:", error);
        if (error.response) {
          console.error("Error response:", error.response.data);
          console.error("Error status:", error.response.status);
          console.error("Error headers:", error.response.headers);
        }
        this.products = []; // Set empty array on error
      }
    },
    async syncFavouritesToBackend() {
      try {
        // Check if user is logged in
        const userId = localStorage.getItem('user_id');
        if (!userId) return;

        // For logged-in users: sync to backend
        await axiosClient.post(apiConfig.favourites.sync, {
          user_id: userId,
          favourites: this.favourites
        });
      } catch (error) {
        console.error('Error syncing favourites:', error);
      }
    }
  },
  async mounted() {
    await this.fetchProducts();
    await this.updateCartCountFromStorage();
    await this.logMenVisit();
    await this.loadFavourites();
  }
};
</script>

<style>
#MensWear {
  margin-top: 150px;
}

body {
  font-family: 'Inter', sans-serif;
  margin: 0;
  padding: 0;
  background-color: #EDE4DA;
}
.container {
  width: 90%;
  margin: auto;
}
.header {
  text-align: center;
  padding: 10px;
  font-size: 40px;
  font-weight: 600;
  color: #333;
}
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  background-color: #EDE4DA;
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
  color: rgb(131, 117, 117);
  font-size: 18px;
}
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
  background: #4D382D;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
}
.banner {
  background-color: #4D382D;
  color: white;
  padding: 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.banner h1 {
  font-size: 28px;
  font-weight: bold;
  color: white;
}
.shop-button {
  background: white;
  color: black;
  padding: 10px 20px;
  border: 2px solid black;
  cursor: pointer;
  font-weight: bold;
}
.filter-container {
  width: 90%;
  margin: 20px auto 0;
  display: flex;
  align-items: center;
  position: relative;
}
.filter-container button {
  background-color: #4D382D;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s ease;
}
.filter-container button:hover {
  background-color: #3a2b22;
}
.filter-dropdown {
  position: absolute;
  top: 55px;
  left: 0;
  background-color: white;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  z-index: 10;
  min-width: 200px;
}
.filter-dropdown label {
  display: block;
  margin-bottom: 10px;
}
.filter-dropdown select {
  width: 100%;
  padding: 8px;
  border-radius: 5px;
  border: 1px solid #ccc;
}
.sort-container {
  width: 90%;
  margin: 20px auto;
  display: flex;
  align-items: center;
  gap: 10px;
}
.products {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 220px));
  gap: 20px;
  padding: 20px 0;
}
.product {
  background: white;
  padding: 15px;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
  position: relative;
}
.product img {
  width: 100%;
  height: auto;
  border-radius: 5px;
}
.product h3 {
  font-size: 16px;
  margin: 10px 0;
  color: #333;
}
.price {
  font-weight: bold;
  color: #555;
}
.size-select, .quantity-select {
  width: 100%;
  padding: 8px;
  margin-top: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
.add-cart {
  background: black;
  color: white;
  padding: 8px 10px;
  border: none;
  margin-top: 10px;
  width: 100%;
  border-radius: 5px;
  cursor: pointer;
}
.add-cart:hover {
  background: #333;
}
.favourite-button {
  background: white;
  color: brown;
  border: 1px solid brown;
  font-size: 20px;
  cursor: pointer;
  position: absolute;
  top: 10px;
  right: 10px;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}
.modal-content {
  background: white;
  padding: 20px;
  width: 90%;
  max-width: 600px;
  border-radius: 5px;
  position: relative;
}
.close-modal {
  position: absolute;
  top: 10px;
  right: 10px;
  background: #4D382D;
  color: white;
  border: none;
  padding: 6px 10px;
  border-radius: 3px;
  cursor: pointer;
  font-size: 14px;
}
.modal-image {
  width: 100%;
  max-width: 300px;
  border-radius: 5px;
  transition: transform 0.2s ease;
}
.modal-image:hover {
  transform: scale(1.1);
}
.modal-price {
  font-weight: bold;
  margin: 10px 0;
}
.color-tag {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 5px 0;
}
.color-square {
  width: 20px;
  height: 20px;
  border-radius: 4px;
  border: 1px solid #ddd;
  cursor: pointer;
  box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease;
}
.color-square:hover {
  transform: scale(1.1);
  box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
}
.color-square.selected {
  border: 2px solid red;
}
</style>


<!--<script>-->
<!--import MenCollectionScript from "@/scripts/pages/products/MenCollectionScript.js";-->

<!--export default MenCollectionScript;-->

<!--</script>-->
<!--<style src="@/styles/pages/products/MenCollectionStyle.css"></style>-->