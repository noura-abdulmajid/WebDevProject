<template>
  <div class="favorites-container">
    <h1>Your Favorites</h1>
    <div v-if="loading" class="loading">Loading favorites...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else-if="favorites.length === 0" class="empty-favorites">
      You haven't marked any products as favorites.
    </div>
    <div v-else class="favorites-items">
      <div class="favorite-item" v-for="(item, index) in favorites" :key="item.P_ID">
        <img :src="item.image" :alt="item.name"/>
        <div class="details">
          <h3>{{ item.name }}</h3>
          <p>{{ item.description }}</p>
          <div class="color-tag">
            <span
                v-for="(color, idx) in item.colors"
                :key="idx"
                class="color-square"
                :style="{ backgroundColor: color }"
                :class="{ selected: item.selectedColor === color }"
                @click="item.selectedColor = color"
            ></span>
          </div>
          <select v-model="item.selectedSize" class="size-select">
            <option value="">Select UK Size</option>
            <option v-for="size in item.sizes" :key="size" :value="size">
              {{ size }}
            </option>
          </select>
          <div class="quantity-selection">
            <button @click="decreaseQuantity(item)">-</button>
            <span>{{ item.selectedQuantity }}</span>
            <button @click="increaseQuantity(item)">+</button>
          </div>
          <p class="price">£{{ item.price.toFixed(2) }}</p>
          <div class="actions">
            <button class="remove-favorite" @click="removeFavorite(item.P_ID)">Remove</button>
            <button class="add-cart" @click="handleAddToCart(item, index)">Add to Cart</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axiosClient from '@/services/axiosClient';
import apiConfig from '@/config/apiURL';

export default {
  name: "FavoriteProducts",
  data() {
    return {
      favorites: [],
      loading: true,
      error: null
    };
  },
  methods: {
    async loadFavorites() {
      try {
        this.loading = true;
        this.error = null;
        
        // Check if user is logged in
        const token = localStorage.getItem('token');
        
        if (token) {
          // For logged-in users: load from backend
          const response = await axiosClient.get(apiConfig.userProfile.favorites, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
          if (response?.data?.favorites) {
            this.favorites = response.data.favorites;
          }
        } else {
          // For guests: load from localStorage
          const savedFavorites = localStorage.getItem('favorites');
          if (savedFavorites) {
            this.favorites = JSON.parse(savedFavorites);
          }
        }
      } catch (error) {
        console.error('Error loading favorites:', error);
        this.error = 'Failed to load favorites. Please try again later.';
      } finally {
        this.loading = false;
      }
    },
    async removeFavorite(productId) {
      try {
        // Check if user is logged in
        const token = localStorage.getItem('token');
        if (token) {
          // For logged-in users: remove from backend
          await axiosClient.delete(apiConfig.userProfile.favorites + '/' + productId);
        } else {
          // For guests: remove from localStorage
          const savedFavorites = JSON.parse(localStorage.getItem('favorites') || '[]');
          const updatedFavorites = savedFavorites.filter(p => p.P_ID !== productId);
          localStorage.setItem('favorites', JSON.stringify(updatedFavorites));
        }
        // Update local state
        this.favorites = this.favorites.filter(p => p.P_ID !== productId);
      } catch (error) {
        console.error('Error removing from favorites:', error);
        alert('Failed to remove from favorites. Please try again.');
      }
    },
    increaseQuantity(item) {
      item.selectedQuantity = (item.selectedQuantity || 1) + 1;
    },
    decreaseQuantity(item) {
      if (item.selectedQuantity > 1) {
        item.selectedQuantity -= 1;
      }
    },
    handleAddToCart(product, index) {
      if (!product.selectedColor) {
        alert("Please select a color before adding to cart.");
        return;
      }
      if (!product.selectedSize) {
        alert("Please select a size before adding to cart.");
        return;
      }
      this.addToCart(product);
      alert("Product added to cart.");
      if (confirm("Do you want to remove this product from your favorites?")) {
        this.removeFavorite(product.P_ID);
      }
    },
    addToCart(product) {
      let cart = JSON.parse(localStorage.getItem("cart") || "[]");
      const quantity = product.selectedQuantity || 1;
      const existingProduct = cart.find(item =>
          item.name === product.name &&
          item.size === product.selectedSize &&
          item.color === product.selectedColor
      );
      if (existingProduct) {
        existingProduct.quantity += quantity;
      } else {
        cart.push({
          name: product.name,
          image: product.image,
          price: product.price,
          description: product.description,
          color: product.selectedColor,
          size: product.selectedSize,
          quantity: quantity
        });
      }
      localStorage.setItem("cart", JSON.stringify(cart));
      window.dispatchEvent(new Event("cart-updated"));
    }
  },
  mounted() {
    this.loadFavorites();
  }
};
</script>

<style scoped>
.favorites-container {
  padding: 20px;
  margin-top: 100px;
}

.loading, .error {
  text-align: center;
  font-size: 18px;
  color: #888;
  margin-top: 20px;
}

.error {
  color: #dc3545;
}

.empty-favorites {
  text-align: center;
  font-size: 18px;
  color: #888;
  margin-top: 20px;
}

.favorites-items {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-top: 20px;
}

.favorite-item {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 15px;
  width: 300px;
  text-align: center;
  background-color: #fff;
}

.favorite-item img {
  width: 100%;
  border-radius: 8px;
}

.details {
  margin-top: 10px;
}

.details h3 {
  margin: 0;
  font-size: 20px;
}

.details p {
  margin: 5px 0;
  font-size: 14px;
  color: #555;
}

.color-tag {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 5px;
  margin: 8px 0;
}

.color-square {
  width: 20px;
  height: 20px;
  border-radius: 4px;
  border: 1px solid #ddd;
  cursor: pointer;
}

.color-square.selected {
  border: 2px solid red;
}

.size-select {
  width: 100%;
  padding: 8px;
  margin-top: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.quantity-selection {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  margin-top: 10px;
}

.quantity-selection button {
  padding: 4px 8px;
  border: none;
  background-color: #007bff;
  color: white;
  border-radius: 4px;
  cursor: pointer;
}

.quantity-selection button:hover {
  background-color: #0056b3;
}

.price {
  font-weight: bold;
  margin: 10px 0;
  font-size: 18px;
  color: #333;
}

.actions {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin-top: 10px;
}

.actions button {
  flex: 1;
  min-width: 120px;
  height: 40px;
  line-height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  margin: 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  box-sizing: border-box;
}

.remove-favorite {
  background-color: #f44336;
  color: white;
}

.remove-favorite:hover {
  background-color: #d32f2f;
}

.add-cart {
  background-color: #4caf50;
  color: white;
}

.add-cart:hover {
  background-color: #388e3c;
}
</style>
