<template>
  <div>
    <h1>Admin Products</h1>

    <div v-if="products.length">
      <ul>
        <li v-for="product in products" :key="product.P_ID">
          <strong>{{ product.p_name }}</strong>
          <p>{{ product.description }}</p>
          <p><strong>Categories:</strong> {{ product.categories.join(", ") }}</p>
          <p><strong>Colours:</strong> {{ product.colours.join(", ") }}</p>
          <p><strong>Price:</strong> ${{ product.price }}</p>
          <p><strong>Stock Status:</strong> {{ product.overall_stock_status }}</p>
          <img :src="product.photo" alt="Product Image" width="150" />
        </li>
      </ul>
    </div>

    <div v-else-if="loading">
      <p>Loading products...</p>
    </div>

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
    };
  },
  methods: {
    fetchProducts() {
      axios
          .get("http://localhost:8000/api/DashShoe/admin/get_products")
          .then((response) => {
            this.products = response.data.products;
            this.loading = false;
          })
          .catch((error) => {
            console.error("Error fetching products:", error);
            this.loading = false;
          });
    },
  },
  mounted() {
    this.fetchProducts();
  },
};
</script>

<style scoped>
h1 {
  color: #333;
}

img {
  display: block;
  margin-top: 10px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>