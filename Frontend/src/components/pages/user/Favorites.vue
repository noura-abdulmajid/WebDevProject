<template>
  <div class="favorites">
    <h1>My Favorites</h1>

    <!-- Favorite List -->
    <div v-if="favorites.length > 0" class="favorite-list">
      <div
          v-for="item in favorites"
          :key="item.P_ID"
          class="favorite-item"
      >
        <img
            :src="item.image"
            :alt="item.name"
            class="item-image"
            @click="openModal(item)"
        />
        <div class="item-details">
          <h3>{{ item.name }}</h3>
          <p>{{ item.description }}</p>
          <p>Price: £{{ item.price }}</p>
          <div class="color-tag">
            <span
                v-for="(color, index) in item.colors"
                :key="index"
                class="color-square"
                :style="{ backgroundColor: color }"
            ></span>
          </div>
          <button
              @click="removeFromFavorites(item)"
              class="remove-btn"
          >
            Remove
          </button>
        </div>
      </div>
    </div>
    <p v-else>No favorites added yet.</p>
    <!-- Modal Area -->
    <div
        class="modal-overlay"
        v-if="selectedProduct"
        @click.self="closeModal"
    >
      <div class="modal-content">
        <button class="close-modal" @click="closeModal">Close</button>
        <h2>{{ selectedProduct.name }}</h2>
        <img
            :src="selectedProduct.image"
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
              :class="{ selected: selectedColor === color }"
              @click.stop="selectedColor = color"
          ></span>
        </div>
        <p>{{ selectedProduct.description }}</p>
        <select class="size-select" v-model="selectedSize">
          <option value="">Select UK Size</option>
          <option
              v-for="size in selectedProduct.sizes"
              :key="size"
              :value="size"
          >
            {{ size }}
          </option>
        </select>
        <button class="add-cart" @click="modalAddToCart">Add to Cart</button>
      </div>
    </div>

  </div>
</template>

<script>
import {ref, onMounted} from "vue";
import axiosClient from '@/services/axiosClient';
import apiConfig from '@/config/apiURL';

export default {
  name: "Favorites",
  setup() {
    const favorites = ref([]);
    const selectedProduct = ref(null);
    const selectedSize = ref("");
    const selectedColor = ref("");

    const loadFavorites = async () => {
      try {
        const token = localStorage.getItem('token');
        if (token) {
          const response = await axiosClient.get(apiConfig.userProfile.favorites, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
          if (response?.data?.favorites) {
            favorites.value = response.data.favorites;
          }
        } else {
          const savedFavorites = localStorage.getItem('favorites');
          if (savedFavorites) {
            favorites.value = JSON.parse(savedFavorites);
          }
        }
      } catch (error) {
        console.error('Error loading favorites:', error);
        const savedFavorites = localStorage.getItem('favorites');
        if (savedFavorites) {
          favorites.value = JSON.parse(savedFavorites);
        }
      }
    };

    const removeFromFavorites = async (item) => {
      try {
        const token = localStorage.getItem('token');
        if (token) {
          await axiosClient.delete(apiConfig.userProfile.favorites + '/' + item.P_ID);
        } else {
          favorites.value = favorites.value.filter((fav) => fav.P_ID !== item.P_ID);
          localStorage.setItem('favorites', JSON.stringify(favorites.value));
        }
      } catch (error) {
        console.error('Error removing from favorites:', error);
        alert('Failed to remove from favorites. Please try again later.');
      }
    };

    const openModal = (product) => {
      selectedProduct.value = product;
      selectedSize.value = "";
      selectedColor.value = product.colors && product.colors.length > 0 ? product.colors[0] : "";
    };

    const closeModal = () => {
      selectedProduct.value = null;
      selectedSize.value = "";
      selectedColor.value = "";
    };

    const modalAddToCart = () => {
      if (!selectedSize.value) {
        alert("Please select a size!");
        return;
      }

      const product = {
        ...selectedProduct.value,
        selectedSize: selectedSize.value,
        selectedColor: selectedColor.value,
        selectedQuantity: 1
      };

      let cart = JSON.parse(localStorage.getItem('cart') || '[]');
      const existingProduct = cart.find(
        item => item.name === product.name && 
               item.size === product.selectedSize &&
               item.color === product.selectedColor
      );

      if (existingProduct) {
        existingProduct.quantity += 1;
      } else {
        cart.push({
          name: product.name,
          image: product.image || '',
          price: product.price || 0,
          description: product.description || '',
          color: product.selectedColor,
          size: product.selectedSize,
          quantity: 1,
        });
      }

      localStorage.setItem('cart', JSON.stringify(cart));
      window.dispatchEvent(new Event("cart-updated"));
      alert("Item added to cart successfully!");
      closeModal();
    };

    onMounted(loadFavorites);

    return {
      favorites,
      selectedProduct,
      selectedSize,
      selectedColor,
      removeFromFavorites,
      openModal,
      closeModal,
      modalAddToCart,
    };
  },
};
</script>

<style scoped>
.favorites {
  overflow-y: auto;
  max-height: none;
  box-sizing: border-box;
}

.favorite-list {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
}

.favorite-item {
  position: relative;
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
  width: calc(50% - 16px);
  /* three
    width: calc(33.33% - 16px);
   */
  box-sizing: border-box;

}

.item-image {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 8px;
}

.item-details {
  flex: 1;
}


.item-details h3 {
  margin: 0;
}

.remove-btn {
  position: absolute;
  bottom: 8px;
  right: 8px;
  background: red;
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  border-radius: 4px;
  font-size: 0.875rem;
}

button.remove-btn:hover {
  background-color: darkred;
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