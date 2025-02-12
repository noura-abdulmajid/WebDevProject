<template>
  <div id="app">
    <header>
      <div class="logo">
        <img src="/src/image/logo three.png" alt="DASH Shoe Logo" />
      </div>
      <nav>
        <ul>
          <li><a href="#">HOME</a></li>
          <li><a href="#">ABOUT</a></li>
          <li><a href="#">SHOPPING</a></li>
          <li><a href="#">CONTACT</a></li>
        </ul>
      </nav>
    </header>
    <div class="header">Women's Collection</div>
    <div class="banner container">
      <div>
        <h1>Discover our latest arrivals and top picks</h1>
        <button class="shop-button">Shop Now</button>
      </div>
      <div>
        <img src="/src/image/womens banner new.png" alt="Illustration" />
      </div>
    </div>
    <div class="products container">
      <div class="product" v-for="(product, index) in products" :key="index">
        <img :src="getImageURL(product.image)" :alt="product.name" />
        <h3>{{ product.name }}</h3>
        <p class="price">{{ product.price }}</p>
        <button class="add-cart">Add to Cart</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "App",
  data() {
    return {
      products: [
        {
          image: "white loafers.avif",
          name: "White Loafers",
          price: "£40",
        },
        {
          image: "white trainers.jpeg",
          name: "White Laced Trainers",
          price: "£82",
        },
        {
          image: "cream heels.jpeg",
          name: "Cream Heels",
          price: "£60",
        },
        {
          image: "flats.avif",
          name: "Flats",
          price: "£25",
        },
        {
          image: "black shoes.jpeg",
          name: "Black Shoes",
          price: "£55",
        },
        {
          image: "red heels.png",
          name: "Red Heels",
          price: "£65",
        },
        {
          image: "https://via.placeholder.com/200",
          name: "Sport Sneakers",
          price: "£70",
        },
        {
          image: "https://via.placeholder.com/200",
          name: "Brown Loafers",
          price: "£50",
        },
      ],
    };
  },
  methods: {
    getImageURL(image) {
      return new URL(`../image/${image}`, import.meta.url).href;
    },
  },
};
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
  font-size: 30px;
  font-weight: 600;
}
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  background-color: #EDE4DA;
  color: white;
}
.logo img {
  width: 150px;
  background-color: transparent;
  mix-blend-mode: multiply;
}
nav ul {
  list-style: none;
  display: flex;
  gap: 30px;
  padding: 0;
  margin: 0;
}
nav a {
  text-decoration: none;
  color: rgb(131, 117, 117);
  font-size: 18px;
}
.banner {
  background-color: #4D382D;
  color: white;
  padding: 40px;
  text-align: left;
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
  border: none;
  cursor: pointer;
  font-weight: bold;
  border: 2px solid black;
}
.products {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  padding: 20px 0;
}
.product {
  background: white;
  padding: 15px;
  text-align: center;
  border-radius: 5px;
}
.product img {
  width: 100%;
  height: auto;
  border-radius: 5px;
}
.product h3 {
  font-size: 16px;
  margin: 10px 0;
}
.price {
  font-weight: bold;
}
.add-cart {
  background: black;
  color: white;
  padding: 8px 10px;
  border: none;
  cursor: pointer;
  margin-top: 10px;
  display: block;
  width: 100%;
}
</style>