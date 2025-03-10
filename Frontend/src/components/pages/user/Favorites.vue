<template>
  <div class="favorites">
    <h1>My Favorites</h1>

    <!-- Favorite List -->
    <div v-if="favorites.length > 0" class="favorite-list">
      <div
          v-for="item in favorites"
          :key="item.name"
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
          <p>Price: ${{ item.price }}</p>
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

export default {
  name: "Favorites",
  setup() {
    const favorites = ref([]);
    const selectedProduct = ref(null);
    const selectedSize = ref("");

    const loadFavorites = () => {
      favorites.value = JSON.parse(localStorage.getItem("favorites")) || [];
    };

    const removeFromFavorites = (item) => {
      favorites.value = favorites.value.filter((fav) => fav.name !== item.name);
      localStorage.setItem("favorites", JSON.stringify(favorites.value));
    };

    const openModal = (product) => {
      selectedProduct.value = product;
      selectedSize.value = "";
    };

    const closeModal = () => {
      selectedProduct.value = null;
      selectedSize.value = "";
    };

    const modalAddToCart = () => {
      if (!selectedSize.value) {
        alert("Please select a size!");
        return;
      }
      alert(
          `Added "${selectedProduct.value.name}" of size "${selectedSize.value}" to your cart!`
      );
      closeModal();
    };

    onMounted(loadFavorites);

    return {
      favorites,
      selectedProduct,
      selectedSize,
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
  padding: 20px;
  overflow-y: auto;
  height: 600px;
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
</style>