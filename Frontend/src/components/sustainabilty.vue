<template>
  <div id="sustainability">
    <header>
      <div class="logo">
        <img src="" alt="DASH Shoe Logo">
      </div>
      <nav>
        <ul>
          <li><a href="#">HOME</a></li>
          <li><a href="#">ABOUT</a></li>
          <li><a href="#">SHOPPING</a></li>

          <li><a href="#">CONTACT</a></li>
        </ul>
      </nav>
      <div class="search-bar">
        <input
            type="text"
            id="searchInput"
            v-model="searchTerm"
            placeholder="Search for shoes..."
        >
        <button>Search</button>
      </div>
    </header>

    <div class="header">Sustainable Picks</div>

    <div class="banner container">
      <div>
        <h1>Discover our latest arrivals and top picks</h1>
        <button class="shop-button">Shop Now</button>
      </div>
      <div>
        <img src="" alt="Illustration">
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
        <img :src="prod.image" :alt="prod.name">
        <h3>{{ prod.name }}</h3>
        <p class="price">£{{ prod.price }}</p>
        <select v-model="prod.selectedSize" class="size-select">
          <option value="">Select UK Size</option>
          <option v-for="size in prod.sizes" :key="size" :value="size">
            {{ size }}
          </option>
        </select>
        <button class="add-cart">Add to Cart</button>
        <!-- Favourite button with white background and brown heart -->
        <button class="favourite-button" @click.stop="toggleFavourite(prod)">
          {{ prod.isFavourite ? '♥' : '♡' }}
        </button>
      </div>
    </div>

    <div class="modal-overlay" v-if="selectedProduct" @click.self="closeModal">
      <div class="modal-content">
        <button class="close-modal" @click="closeModal">Close</button>
        <h2>{{ selectedProduct.name }}</h2>
        <img :src="selectedProduct.image" alt="Detail Image" class="modal-image">
        <p class="modal-price">£{{ selectedProduct.price }}</p>
        <p>{{ selectedProduct.description }}</p>
        <select v-model="selectedSize" class="size-select" style="margin-top: 10px;">
          <option value="">Select UK Size</option>
          <option v-for="size in selectedProduct.sizes" :key="size" :value="size">
            {{ size }}
          </option>
        </select>
        <button class="add-cart" style="margin-top: 15px;" @click="modalAddToCart">
          Add to Cart
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'sustainability',
  data() {
    return {
      showDropdown: false,
      selectedFilter: '',
      selectedCategory: '',
      selectedColor: '',
      selectedSort: 'recommended',
      searchTerm: '',
      selectedProduct: null,
      selectedSize: '',
      favourites: [],
      categories: ['Sneakers', 'Boots', 'Sandals'],
      colors: ['Red', 'Blue', 'Green', 'Black'],
      products: [
        {
          name: 'Red Sneaker',
          image: 'path/to/image1.jpg',
          price: 50,
          sizes: [5, 6, 7, 8],
          description: 'A cool red sneaker.',
          color: 'Red',
          category: 'Sneakers',
          isFavourite: false,
          selectedSize: '',
          dateAdded: '2025-03-01'
        },
        {
          name: 'Black Boot',
          image: 'path/to/image2.jpg',
          price: 80,
          sizes: [7, 8, 9, 10],
          description: 'A sturdy black boot.',
          color: 'Black',
          category: 'Boots',
          isFavourite: false,
          selectedSize: '',
          dateAdded: '2025-03-05'
        },
        {
          name: 'Blue Sandal',
          image: 'path/to/image3.jpg',
          price: 40,
          sizes: [4, 5, 6, 7],
          description: 'A comfortable blue sandal.',
          color: 'Blue',
          category: 'Sandals',
          isFavourite: false,
          selectedSize: '',
          dateAdded: '2025-03-03'
        }
      ]
    }
  },
  computed: {
    filteredProducts() {
      return this.products.filter(prod => {
        if (this.selectedCategory && prod.category !== this.selectedCategory) return false;
        if (this.selectedColor && prod.color !== this.selectedColor) return false;
        if (this.searchTerm) {
          const search = this.searchTerm.toLowerCase();
          return prod.name.toLowerCase().includes(search) ||
              prod.description.toLowerCase().includes(search) ||
              prod.color.toLowerCase().includes(search);
        }
        return true;
      });
    },
    sortedProducts() {
      let sorted = [...this.filteredProducts];
      if (this.selectedSort === 'priceDesc') {
        sorted.sort((a, b) => b.price - a.price);
      } else if (this.selectedSort === 'priceAsc') {
        sorted.sort((a, b) => a.price - b.price);
      } else if (this.selectedSort === 'whatsNew') {
        sorted.sort((a, b) => new Date(b.dateAdded) - new Date(a.dateAdded));
      }
      return sorted;
    }
  },
  methods: {
    openProduct(prod) {
      this.selectedProduct = prod;
    },
    closeModal() {
      this.selectedProduct = null;
    },
    modalAddToCart() {
      alert('Added to cart: ' + this.selectedProduct.name + ' Size: ' + this.selectedSize);
      this.closeModal();
    },
    toggleFavourite(product) {
      product.isFavourite = !product.isFavourite;
      // Favourites are only tracked locally in this component.
      if (product.isFavourite) {
        this.favourites.push(product);
      } else {
        const index = this.favourites.findIndex(p => p.name === product.name);
        if (index !== -1) {
          this.favourites.splice(index, 1);
        }
      }
    }
  }
}
</script>

<style scoped>
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
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
.size-select {
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
  background: rgba(0,0,0,0.6);
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