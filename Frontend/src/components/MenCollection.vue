<template>
  <div class="test-background">
    <div id="childrensWear">
      <!-- Header Section -->
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

     
      <div class="header">Men's Collection</div>

      
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
            :key="prod.id"
            @click="openProduct(prod)"
        >
          <img :src="prod.image" :alt="prod.name">
          <h3>{{ prod.name }}</h3>
          <p class="price">£{{ prod.price }}</p>
          <!-- Product Star Rating -->
          <div class="rating">
            <span v-for="star in 5" :key="star">
              {{ star <= prod.rating ? '★' : '☆' }}
            </span>
          </div>
          <select v-model="prod.selectedSize" class="size-select">
            <option value="">Select UK Size</option>
            <option v-for="size in prod.sizes" :key="size" :value="size">
              {{ size }}
            </option>
          </select>
          <button class="add-cart">Add to Cart</button>
          <button class="favourite-button" @click.stop="toggleFavourite(prod)">
            {{ prod.isFavourite ? '♥' : '♡' }}
          </button>
        </div>
      </div>

     

 
      <div class="reviews-carousel container">
        <h2>Customer Reviews</h2>
        <div class="carousel-container">
          <button class="carousel-control left" @click="prevReview">‹</button>
          <div class="carousel-slide">
            <div class="review" v-for="(review, index) in reviews" :key="index" v-show="index === currentReview">
              <p>"{{ review.text }}"</p>
              <!-- Review Star Rating -->
              <div class="review-rating">
                <span v-for="star in 5" :key="star">
                  {{ star <= review.rating ? '★' : '☆' }}
                </span>
              </div>
              <h4>- {{ review.name }}</h4>
            </div>
          </div>
          <button class="carousel-control right" @click="nextReview">›</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ChildrensWear',
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
          id: 1,
          name: 'Red Sneaker',
          image: 'path/to/image1.jpg',
          price: 50,
          sizes: [5, 6, 7, 8],
          description: 'A cool red sneaker.',
          color: 'Red',
          category: 'Sneakers',
          isFavourite: false,
          selectedSize: '',
          dateAdded: '2025-03-01',
          rating: 4
        },
        {
          id: 2,
          name: 'Black Boot',
          image: 'path/to/image2.jpg',
          price: 80,
          sizes: [7, 8, 9, 10],
          description: 'A sturdy black boot.',
          color: 'Black',
          category: 'Boots',
          isFavourite: false,
          selectedSize: '',
          dateAdded: '2025-03-05',
          rating: 5
        },
        {
          id: 3,
          name: 'Blue Sandal',
          image: 'path/to/image3.jpg',
          price: 40,
          sizes: [4, 5, 6, 7],
          description: 'A comfortable blue sandal.',
          color: 'Blue',
          category: 'Sandals',
          isFavourite: false,
          selectedSize: '',
          dateAdded: '2025-03-03',
          rating: 3
        }
      ],
      reviews: [
        { name: 'Alice', text: 'Great quality and design!', rating: 5 },
        { name: 'Bob', text: 'Comfortable and stylish, highly recommended.', rating: 4 },
        { name: 'Charlie', text: 'Good value for money and amazing customer service.', rating: 5 }
      ],
      currentReview: 0
    };
  },
  computed: {
    filteredProducts() {
      return this.products.filter(prod => {
        if (this.selectedCategory && prod.category !== this.selectedCategory) return false;
        if (this.selectedColor && prod.color !== this.selectedColor) return false;
        if (this.searchTerm) {
          const search = this.searchTerm.toLowerCase();
          return (
              prod.name.toLowerCase().includes(search) ||
              prod.description.toLowerCase().includes(search) ||
              prod.color.toLowerCase().includes(search)
          );
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
      
      this.$router.push({ name: 'ProductDetail', params: { id: prod.id } });
    },
    closeModal() {
      this.selectedProduct = null;
    },
    modalAddToCart() {
      alert(
          'Added to cart: ' +
          this.selectedProduct.name +
          ' Size: ' +
          this.selectedSize
      );
      this.closeModal();
    },
    toggleFavourite(product) {
      product.isFavourite = !product.isFavourite;
      if (product.isFavourite) {
        this.favourites.push(product);
      } else {
        const index = this.favourites.findIndex(p => p.id === product.id);
        if (index !== -1) {
          this.favourites.splice(index, 1);
        }
      }
    },
    prevReview() {
      this.currentReview =
          this.currentReview === 0 ? this.reviews.length - 1 : this.currentReview - 1;
    },
    nextReview() {
      this.currentReview = (this.currentReview + 1) % this.reviews.length;
    }
  }
};
</script>

<style scoped>
/* General Styles */
.test-background {
  background-color: white;
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

/* Header */
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

/* Banner */
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

/* Filter & Sort */
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

/* Products */
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
/* Product Star Ratings */
.rating {
  margin: 8px 0;
  font-size: 18px;
  color: #FFA500;
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

/* Reviews Carousel */
.reviews-carousel {
  background-color: #f9f9f9;
  padding: 30px 0;
  margin-top: 30px;
  text-align: center;
}
.carousel-container {
  display: flex;
  align-items: center;
  justify-content: center;
}
.carousel-control {
  background: none;
  border: none;
  font-size: 2rem;
  cursor: pointer;
  padding: 0 15px;
}
.carousel-slide {
  width: 50%;
  min-width: 250px;
  padding: 0 20px;
}
.review {
  font-style: italic;
  margin: 0;
}
.review-rating {
  margin: 10px 0;
  font-size: 18px;
  color: #FFA500;
}
</style>
