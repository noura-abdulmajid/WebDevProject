<template>
  <div class="product-view container">
    <div class="layout">

      <div class="left-column">

        <section class="product-description-section">
          <div class="product-main">

            <div class="product-image">
              <img :src="product.image" :alt="product.name" />
            </div>

            <div class="product-info">
              <h1 class="product-name">{{ product.name }}</h1>
              <p class="product-price">£{{ product.price }}</p>
              <div class="product-rating">
                <span v-for="star in 5" :key="star">
                  {{ star <= product.rating ? '★' : '☆' }}
                </span>
              </div>
              <p class="product-description">{{ product.description }}</p>

              <div class="product-details">
                <h3>Product Details</h3>
                <ul>
                  <li v-for="(detail, index) in product.details" :key="index">
                    {{ detail }}
                  </li>
                </ul>
              </div>

              <div class="add-to-cart-section">
                <select v-model="selectedSize" class="size-select">
                  <option value="" disabled>Select Size</option>
                  <option v-for="size in product.sizes" :key="size" :value="size">
                    {{ size }}
                  </option>
                </select>
                <button class="add-cart" @click="addToCart">
                  Add to Cart
                </button>
              </div>
            </div>
          </div>
        </section>


        <section class="also-brought-section">
          <h2>Also bought with...</h2>
          <div class="also-brought-items">
            <div class="also-brought-item" v-for="(item, index) in alsoBroughtWith" :key="item.id">
              <img :src="item.image" :alt="item.name" />
              <h4>{{ item.name }}</h4>
              <p class="price">£{{ item.price }}</p>
              <button class="add-cart" @click="addAccessoryToCart(item)">
                Add to Cart
              </button>
            </div>
          </div>
        </section>
      </div>


      <div class="right-column">

        <section class="reviews-section">
          <h2>See what others have said</h2>
          <div class="reviews">
            <div class="review" v-for="(review, index) in reviews" :key="index">
              <p class="review-text">"{{ review.text }}"</p>
              <div class="review-rating">
                <span v-for="star in 5" :key="star">
                  {{ star <= review.rating ? '★' : '☆' }}
                </span>
              </div>
              <p class="review-author">- {{ review.name }}</p>
            </div>
          </div>
        </section>

        <section class="recommended-section">
          <h2>Recommended Products</h2>
          <div class="carousel-wrapper">
            <button class="carousel-arrow left" @click="scrollLeft">‹</button>
            <div class="recommended-products" ref="carousel">
              <div class="recommended-product" v-for="(item, index) in recommendedProducts" :key="item.id">
                <img :src="item.image" :alt="item.name" />
                <h4>{{ item.name }}</h4>
                <p class="price">£{{ item.price }}</p>

                <select v-model="selectedSize" class="size-select">

                  <option value="" disabled>Select Size</option>
                  <option v-for="size in item.sizes" :key="size" :value="size">
                    {{ size }}
                  </option>
                </select>
                <button class="add-cart" @click="addRecommendedToCart(item)">
                  Add to Cart
                </button>
              </div>
            </div>
            <button class="carousel-arrow right" @click="scrollRight">›</button>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ProductView",
  props: ["id"],
  data() {
    return {
      product: {},
      selectedSize: "",
      reviews: [
        {
          name: "Alice",
          text: "Amazing product! Highly recommended. The premium materials and innovative design really set this shoe apart.",
          rating: 5
        },
        {
          name: "Bob",
          text: "Good value for money. The build quality is impressive and the detailed craftsmanship is evident in every stitch.",
          rating: 4
        },
        {
          name: "Charlie",
          text: "Comfortable and stylish. The durable material feels great and the extra cushioning provides superb support.",
          rating: 5
        }
      ],
      recommendedProducts: [
        {
          id: 2,
          name: "Black Boot",
          image: "path/to/image2.jpg",
          price: 80,
          sizes: [7, 8, 9, 10]
        },
        {
          id: 3,
          name: "Blue Sandal",
          image: "path/to/image3.jpg",
          price: 40,
          sizes: [4, 5, 6, 7]
        },
        {
          id: 4,
          name: "Green Trainer",
          image: "path/to/image4.jpg",
          price: 55,
          sizes: [6, 7, 8, 9]
        }
      ],
      alsoBroughtWith: [
        {
          id: 101,
          name: "Shoe Cleaner",
          image: "path/to/shoe_cleaner.jpg",
          price: 10
        },
        {
          id: 102,
          name: "Socks",
          image: "path/to/socks.jpg",
          price: 5
        },
        {
          id: 103,
          name: "Insoles",
          image: "path/to/insoles.jpg",
          price: 8
        },
        {
          id: 104,
          name: "Suede Cleaning Kit",
          image: "path/to/suede_cleaning_kit.jpg",
          price: 12
        }
      ],
      products: [
        {
          id: 1,
          name: "Red Sneaker",
          image: "path/to/image1.jpg",
          price: 50,
          sizes: [5, 6, 7, 8],
          description: "A cool red sneaker with a modern design.",
          rating: 4,
          details: [
            "Breathable material",
            "Lightweight design",
            "Durable sole",
            "Premium synthetic leather upper"
          ]
        },
        {
          id: 2,
          name: "Black Boot",
          image: "path/to/image2.jpg",
          price: 80,
          sizes: [7, 8, 9, 10],
          description: "A sturdy black boot perfect for rough terrain.",
          rating: 5,
          details: [
            "Water-resistant",
            "Reinforced toe",
            "Comfortable padding",
            "High-quality leather upper"
          ]
        },
        {
          id: 3,
          name: "Blue Sandal",
          image: "path/to/image3.jpg",
          price: 40,
          sizes: [4, 5, 6, 7],
          description: "A comfortable blue sandal ideal for summer.",
          rating: 3,
          details: [
            "Adjustable straps",
            "Soft sole",
            "Easy to clean",
            "Eco-friendly synthetic material"
          ]
        },
        {
          id: 4,
          name: "Green Trainer",
          image: "path/to/image4.jpg",
          price: 55,
          sizes: [6, 7, 8, 9],
          description: "A trendy green trainer for everyday wear.",
          rating: 4,
          details: [
            "Mesh upper",
            "Cushioned insole",
            "Durable outsole",
            "Breathable fabric lining"
          ]
        }
      ],
      recommendedSelectedSizes: {}
    };
  },
  created() {
    const prod = this.products.find(p => p.id === Number(this.id));
    if (prod) {
      this.product = prod;
    }
  },
  methods: {
    addToCart() {
      if (!this.selectedSize) {
        alert("Please select a size.");
        return;
      }
      alert(`Added ${this.product.name} (Size: ${this.selectedSize}) to your cart.`);
    },
    addRecommendedToCart(item) {
      const size = this.recommendedSelectedSizes[item.id];
      if (!size) {
        alert(`Please select a size for ${item.name}.`);
        return;
      }
      alert(`Added ${item.name} (Size: ${size}) to your cart.`);
    },
    addAccessoryToCart(item) {
      alert(`Added ${item.name} to your cart.`);
    },
    openProduct(item) {
      this.$router.push({ name: "ProductDetail", params: { id: item.id } });
    },
    scrollLeft() {
      this.$refs.carousel.scrollBy({ left: -220, behavior: "smooth" });
    },
    scrollRight() {
      this.$refs.carousel.scrollBy({ left: 220, behavior: "smooth" });
    }
  }
};
</script>

<style scoped>

.product-view {
  background-color: #EDE4DA;
  padding: 10px;
  margin-top: 100px;
}


.layout {
  display: flex;
  gap: 20px;
}


.left-column {
  flex: 2;
  display: flex;
  flex-direction: column;
  gap: 20px;
}


.right-column {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 20px;
}


.product-description-section {
  background: #fff;
  padding: 20px 10px 10px;
  margin-top: 20px;
  border-radius: 4px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}


.product-main {
  display: flex;
  flex-direction: row;
  gap: 10px;
}


.product-image {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
}

.product-image img {
  width: 100%;
  border-radius: 4px;
  object-fit: contain;
}


.product-info {
  flex: 1;
  padding: 5px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.product-name {
  font-size: 1.8em;
  margin-bottom: 5px;
  color: #4D382D;
}

.product-price {
  font-size: 1.4em;
  margin-bottom: 5px;
  color: #4D382D;
}

.product-rating span {
  font-size: 1.1em;
  color: #FFA500;
  margin-right: 2px;
}

.product-description {
  margin: 10px 0;
  line-height: 1.4;
  color: #333;
}

.product-details {
  margin-bottom: 10px;
}

.product-details h3 {
  margin-bottom: 5px;
  color: #4D382D;
}

.product-details ul {
  list-style: disc;
  padding-left: 15px;
  color: #555;
}


.add-to-cart-section {
  margin-top: 10px;
}

.size-select {
  padding: 6px;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-right: 5px;
}

.add-cart {
  background-color: #4D382D;
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.add-cart:hover {
  background-color: #3a2b22;
}


.also-brought-section {
  background: #fff;
  padding: 10px;
  border-radius: 4px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.also-brought-section h2 {
  margin-bottom: 10px;
  color: #4D382D;
}

.also-brought-items {
  display: flex;
  overflow-x: auto;
  gap: 5px;
  padding: 5px 0;
}

.also-brought-item {
  flex: 0 0 auto;
  width: 180px;
  text-align: center;
  background: #fff;
  padding: 5px;
  border: 1px solid #eee;
  border-radius: 4px;
  cursor: pointer;
  transition: transform 0.2s;
}

.also-brought-item:hover {
  transform: scale(1.03);
}

.also-brought-item img {
  width: 100%;
  border-radius: 4px;
}


.reviews-section {
  background: #fff;
  padding: 10px;
  border-radius: 4px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.reviews-section h2 {
  margin-bottom: 10px;
  color: #4D382D;
}

.reviews {
  display: block;
}

.review {
  background: #f7f7f7;
  padding: 10px;
  border-radius: 4px;
  margin-bottom: 10px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.review-text {
  font-style: italic;
  margin-bottom: 5px;
  color: #333;
}

.review-rating span {
  color: #FFA500;
  margin-right: 2px;
}

.review-author {
  text-align: right;
  font-weight: bold;
  color: #4D382D;
}


.recommended-section {
  background: #fff;
  padding: 10px;
  border-radius: 4px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.recommended-section h2 {
  margin-bottom: 10px;
  color: #4D382D;
}


.carousel-wrapper {
  display: flex;
  align-items: center;
}

.carousel-arrow {
  background-color: transparent;
  border: none;
  font-size: 1.8em;
  color: #4D382D;
  cursor: pointer;
  padding: 5px;
}

.recommended-products {
  display: flex;
  overflow-x: auto;
  scroll-behavior: smooth;
  gap: 5px;
  flex: 1;
  padding: 5px 0;
}

.recommended-product {
  flex: 0 0 auto;
  width: 180px;
  text-align: center;
  background: #fff;
  padding: 5px;
  border: 1px solid #eee;
  border-radius: 4px;
  transition: transform 0.2s;
}

.recommended-product:hover {
  transform: scale(1.03);
}

.recommended-product img {
  width: 100%;
  border-radius: 4px;
}

.recommended-product h4 {
  margin: 5px 0;
  color: #4D382D;
}

.recommended-product .price {
  font-weight: bold;
  color: #4D382D;
}
</style>
