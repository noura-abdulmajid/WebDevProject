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
      <div class="actions-section">
        <button @click="goBack" class="styled-back-button">Back to Summary</button>
        <button @click="addProduct" class="styled-add-button">Add Product</button>
      </div>

      <!-- Product Grid -->
      <div class="products-grid">
        <div
            v-for="product in filteredProducts"
            :key="product.P_ID"
            class="product-card"
        >
          <img :src="product.photo" alt="Product Image" class="product-card__image"/>
          <div class="product-card__content">

            <!-- Product Name and Description -->
            <strong class="product-card__title">{{ product.p_name }}</strong>
            <p class="product-card__description">{{ product.description }}</p>

            <!-- Categories -->
            <p><strong>Categories:</strong> {{ product.categories.join(", ") }}</p>
            <div class="tag-list">
              <span v-for="(category, index) in product.categories" :key="`cat-${index}`" class="tag">
                {{ category }}
              </span>
            </div>

            <!-- Sizes Debug Use-->
            <!--
            <div class="tag-list">
              <span v-for="(inventory, index) in product.inventory" :key="`size-${index}`" class="size-tag">
                {{ inventory.size }}
              </span>
            </div>
            -->

            <!-- Colours -->
            <div class="tag-list">
              <div
                  v-for="(color, index) in product.colours"
                  :key="`color-${index}`"
                  class="color-tag"
                  @click="showStockDialog(color, product.inventory)"
              >
              <span
                  class="color-square"
                  :style="{ backgroundColor: color }"
                  title="Click to view stock"
              ></span>
              </div>
            </div>

            <!-- Stock Inventory Dialog -->
            <div v-if="activeDialog === 'stockDialog'" class="dialog-overlay">
              <div class="dialog-box">
                <h2>Stock Inventory</h2>
                <p><strong>Selected Color:</strong>
                  <span :style="{ background: selectedColor }" class="color-box"></span>
                </p>
                <table class="inventory-table">
                  <thead>
                  <tr>
                    <th>Size</th>
                    <th>Stock Level</th>
                    <th>Status</th>
                    <th>Price</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="filteredInventory.length > 0" v-for="(item, index) in filteredInventory"
                      :key="`stock-${index}`">
                    <td>{{ item.size }}</td>
                    <td>{{ item.stock_level }}</td>
                    <td :class="getStockStatusClass(item.stock_status)">
                      {{ item.stock_status }}
                    </td>
                    <td>{{ item.price }}</td>
                  </tr>
                  <tr v-else>
                    <td colspan="4" style="text-align: center; color: #888;">
                      No Inventory Available.
                    </td>
                  </tr>
                  </tbody>
                </table>
                <div class="dialog-actions">
                  <button @click="closeStockDialog" class="cancel-button">Close</button>
                </div>
              </div>
            </div>

            <!-- Price -->
            <p><strong>Price:</strong> ${{ product.price }}</p>

          </div>

          <!-- Edit and Delete Actions -->
          <div class="product-actions">
            <div>
              <button @click="editProduct(product)" class="product-actions__edit">Edit</button>
              <button @click="deleteProduct(product.P_ID)" class="product-actions__delete">Delete</button>
            </div>

            <span :class="getStockStatusClass(product.overall_stock_status)" class="stock-status">
              {{ product.overall_stock_status }}
            </span>
          </div>

          <!-- Edit Product Dialog -->
          <div v-if="activeDialog === 'editProduct'" class="dialog-overlay">
            <div class="dialog-box">
              <h2>{{ isAddingProduct ? 'Add Product' : 'Edit Product' }}</h2>
              <label>
                Product Image:
                <div class="image-edit-container">
                  <img :src="editProductData.photo" alt="Product Image" class="editable-image"/>
                  <input type="file" @change="uploadImage" accept="image/*" class="upload-input"/>
                </div>
              </label>


              <label>
                Name:
                <input type="text" v-model="editProductData.p_name"/>
              </label>
              <label>
                Description:
                <textarea v-model="editProductData.description"></textarea>
              </label>
              <label>
                Price:
                <input type="number" v-model="editProductData.price"/>
              </label>
              <label>
                Categories:
                <div class="tag-list">
                  <div
                      v-for="(category, index) in editProductData.categories"
                      :key="'cat-' + index"
                      class="category-edit-container"
                  >
                    <input
                        type="text"
                        v-model="editProductData.categories[index]"
                        class="tag-input"
                        placeholder="Enter category"
                    />
                    <button @click="removeCategory(index)" class="remove-category">Delete</button>
                  </div>

                  <button @click="addCategory" class="add-category">
                    + Add Category
                  </button>
                </div>
              </label>

              <div>
                Colours:
                <button @click="addColor" class="add-color">+ Add Colour</button>
                <div :key="editProductData.colours.join('-')">
                  <div
                      v-for="(color, index) in editProductData.colours"
                      :key="`${color}-${index}`"
                      class="color-section"
                  >
                    <div class="color-edit-container">
                      <input
                          type="color"
                          :value="color"
                          @input="updateColor($event, index)"
                          class="color-picker"
                      />
                      <span class="color-code">{{ color }}</span>
                      <button @click="removeColor(index)" class="remove-color">Remove</button>
                    </div>
                    <div>
                      Inventory :
                      <table class="inventory-table">
                        <thead>
                        <tr>
                          <th>Size</th>
                          <th>Stock Level</th>
                          <th>Price</th>
                          <th>Stock Status</th>
                          <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in getInventoryByColor(color)" :key="item.inventoryId">
                          <td>
                            <input type="text" v-model="item.size" placeholder="Size"/>
                          </td>
                          <td>
                            <input type="number" v-model="item.stock_level" placeholder="Stock Level"/>
                          </td>
                          <td>
                            <input type="number" step="0.01" v-model="item.price" placeholder="Price"/>
                          </td>
                          <td>
                            <select v-model="item.stock_status" class="styled-select">
                              <option value="in_stock">In Stock</option>
                              <option value="low_stock">Low Stock</option>
                              <option value="out_of_stock">Out Of Stock</option>
                            </select>
                          </td>
                          <td>
                            <button @click.stop="removeInventoryItem(item.inventoryId)" class="delete-inventory">
                              Delete
                            </button>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="5" style="text-align: center;">
                            <button @click.stop="addInventoryForColor(color)" class="add-color">Add Size</button>
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <label>
                Stock Status:
                <select
                    v-model="editProductData.overall_stock_status"
                    class="styled-select"
                    :class="{
                      'select-in-stock': editProductData.overall_stock_status === 'in_stock',
                      'select-out-of-stock': editProductData.overall_stock_status === 'out_of_stock',
                      'select-discontinued': editProductData.overall_stock_status === 'discontinued'
                    }"
                >
                  <option value="in_stock">In Stock</option>
                  <option value="out_of_stock">Out of Stock</option>
                  <option value="discontinued">Discontinued</option>
                </select>
              </label>

              <div class="dialog-actions">
                <button @click="saveProductEdit" class="save-button">Save</button>
                <button @click="closeEditDialog" class="cancel-button">Cancel</button>
              </div>
            </div>
          </div>
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
      selectedColor: null,
      filteredInventory: [],
      activeDialog: null,
      deletionInProgress: false,
      isAddingProduct: false,
      editProductData: {
        p_name: "",
        description: "",
        price: 0,
        inventory: [],
        categories: [""],
        colours: [],
        overall_stock_status: "in_stock",
      },


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
            this.products = response.data.products.map((product) => {
              let categories = [];
              let colours = [];
              let inventory = product.inventory || [];

              if (typeof product.categories === "string") {
                try {
                  categories = JSON.parse(product.categories);
                } catch (e) {
                  categories = product.categories.split(",").map((item) => item.trim());
                }
              } else if (Array.isArray(product.categories)) {
                categories = product.categories;
              }

              if (typeof product.colours === "string") {
                try {
                  colours = JSON.parse(product.colours);
                } catch (e) {
                  colours = product.colours.split(",").map((color) => color.trim());
                }
              } else if (Array.isArray(product.colours)) {
                colours = product.colours;
              }

              inventory = (product.inventory || []).map(item => ({
                ...item,
                inventoryId: item.inventoryId || (Date.now() + Math.random())
              }));

              return {
                ...product,
                categories,
                colours,
                inventory,
              };
            });

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
    addColor() {
      this.editProductData.colours.push("#000000");
    },

    goBack() {
      this.view = "summary";
      this.selectedCategory = null;
    },
    uploadImage(event) {
      const file = event.target.files[0];
      if (!file) {
        alert("Please select a file!");
        return;
      }

      const formData = new FormData();
      formData.append("image", file);

      axios.post("http://localhost:8000/api/DashShoe/admin/upload_image", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
          .then((response) => {
            if (response.data && response.data.image_url) {
              this.editProductData.photo = response.data.image_url;
              alert("Image uploaded successfully!");
            } else {
              alert("Image upload failed!");
            }
          })
          .catch((error) => {
            console.error("Image upload failed:", error);
            alert("An error occurred during the upload process. Please try again.");
          });
    },
    getNextProductId() {
      if (this.products && this.products.length > 0) {
        const maxId = Math.max(...this.products.map(product => product.P_ID));
        return maxId + 1;
      }
      return 1;
    },
    addProduct() {
      this.isAddingProduct = true;
      this.activeDialog = "editProduct";
      this.editProductData = {
        p_name: "",
        description: "",
        price: 0,
        inventory: [],
        categories: [""],
        colours: [],
        overall_stock_status: "in_stock",
        photo: "",
        gender_target: this.selectedCategory || "unisex",
      };
    },
    editProduct(product) {
      this.activeDialog = 'editProduct';
      this.editProductData = JSON.parse(JSON.stringify(product));
    },
    closeEditDialog() {
      this.activeDialog = null;
      this.isAddingProduct = false;
      this.editProductData = {
        p_name: "",
        description: "",
        price: 0,
        categories: [""],
        colours: [],
        inventory: []
      };
    },
    addCategory() {
      this.editProductData.categories.push("");
    },
    removeCategory(index) {
      this.editProductData.categories.splice(index, 1);
    },
    updateColor(event, index) {

      this.$set(this.editProductData.colours, index, event.target.value);
    },
    removeColor(index) {
      if (confirm("Are you sure you want to delete this colour and the associated inventory data?？")) {
        const removedColor = this.editProductData.colours[index];
        console.log("Before removal:", this.editProductData.colours);
        this.editProductData.colours.splice(index, 1);
        console.log("After removal:", this.editProductData.colours);
        this.editProductData.inventory = this.editProductData.inventory.filter(
            (item) =>
                item.color?.toLowerCase().trim() !== removedColor.toLowerCase().trim()
        );
        alert(`The colour ${removedColor} and the associated inventory data have been successfully deleted.`);
      }
    },


    getInventoryByColor(color) {
      return this.editProductData.inventory.filter(
          (item) => item.color?.toLowerCase() === color?.toLowerCase()
      );
    },
    addInventoryForColor(color) {
      const newInventoryItem = {
        inventoryId: Date.now() + Math.random(),
        P_ID: this.editProductData.P_ID,
        color: color,
        size: "",
        stock_level: 0,
        price: 0,
        stock_status: "in_stock",
      };
      this.editProductData.inventory = [
        ...this.editProductData.inventory,
        newInventoryItem,
      ];

      alert(`Successfully added size to colour: ${color}`);
    },
    removeInventoryItem(inventoryId) {
      this.editProductData.inventory = this.editProductData.inventory.filter(
          item => item.inventoryId !== inventoryId
      );
      alert("Inventory item removed successfully.");
    },


    showStockDialog(color, inventory) {
      this.activeDialog = 'stockDialog';
      this.selectedColor = color;
      this.filteredInventory = inventory.filter(item =>
          item.color?.toLowerCase().trim() === color?.toLowerCase().trim()
      );
    },
    closeStockDialog() {
      this.activeDialog = null;
      this.selectedColor = null;
      this.filteredInventory = [];
    }
    ,
    ensureInventoryHasPID() {
      this.editProductData.inventory = this.editProductData.inventory.map((item) => ({
        ...item,
        P_ID: this.editProductData.P_ID
      }));
    }
    ,
    saveProductEdit() {
      this.ensureInventoryHasPID();

      if (this.isAddingProduct) {

        const newProduct = {...this.editProductData, P_ID: this.getNextProductId()};
        this.products.push(newProduct);
        alert("Product added successfully!");
      } else {
        const productData = {
          ...this.editProductData,
          inventory: [...this.editProductData.inventory],
        };

        const url = `http://localhost:8000/api/DashShoe/admin/update_product/${this.editProductData.P_ID}`;

        axios.put(url, productData)
            .then((response) => {
              const productIndex = this.products.findIndex(
                  (prod) => prod.P_ID === this.editProductData.P_ID
              );
              if (productIndex !== -1) {
                this.products[productIndex] = {...this.editProductData};
              }
              alert("Product updated successfully!");
              this.closeEditDialog();
            })
            .catch((error) => {
              console.error("Error updating product:", error);
              alert("Failed to update product. Please try again.");
            });
      }

    },

    deleteProduct(productId) {
      if (confirm("Are you sure you want to delete this product?")) {
        axios
            .delete(`http://localhost:8000/api/DashShoe/admin/delete_product/${productId}`)
            .then((response) => {
              if (response.status === 200 || response.data.success) {
                this.products = this.products.filter((product) => product.P_ID !== productId);
                alert("Product deleted successfully!");
              } else {
                alert("Failed to delete product. Please try again.");
              }
            })
            .catch((error) => {
              console.error("Error deleting product:", error);
              alert("An error occurred while deleting the product.");
            });
      }
    },
    getStockStatusClass(status) {
      return {
        "stock-status--in-stock": status === "in_stock",
        "stock-status--out-of-stock": status === "out_of_stock",
        "stock-status--discontinued": status === "discontinued",
        "stock-status": true,
      };
    }
    ,

  },
  mounted() {
    this.fetchProducts();
  },
};

</script>

<style>
/* General Container Styling */
.products-container {
  max-height: 100vh;
  overflow-y: auto;
  padding: 20px;
  padding-bottom: 100px;

  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  color: #374151;
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

/* Add Product Button Styling */
.styled-add-button {
  background: #4caf50;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  margin-left: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: background-color 0.3s, transform 0.3s;
}

.styled-add-button:hover {
  background-color: #43a047;
  transform: translateY(-2px);
}

/* Product Actions Button Styling */
.product-actions {
  margin-top: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
}

.product-actions button {
  padding: 6px 12px;
  border: none;
  border-radius: 10px;
  font-size: 0.9rem;
  cursor: pointer;
  transition: transform 0.3s, background-color 0.3s;
}

.product-actions button:first-child {
  background-color: #0288d1;
  color: #fff;
}

.product-actions button:first-child:hover {
  background-color: #0277bd;
  transform: translateY(-2px);
}

.product-actions button:last-child {
  background-color: #f44336;
  color: #fff;
}

.product-actions button:last-child:hover {
  background-color: #d32f2f;
  transform: translateY(-2px);
}

.product-card__title {
  font-weight: bold;
  font-size: 1.2rem;
  margin-bottom: 10px;
}

.product-card__description {
  margin-bottom: 10px;
}

.product-card__image {
  max-width: 100%;
  height: auto;
  border-radius: 10px;
}

.tag-list {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 10px;
}

.tag {
  font-family: "Inter", sans-serif;
  font-size: 14px;
  color: #4318d1;
  padding: 4px 12px;
  border-radius: 9999px;
  background-color: #f3f4f6;
}

.stock-status {
  font-size: 0.9rem;
  font-weight: bold;
  padding: 5px 10px;
  border-radius: 8px;
  text-transform: capitalize;
}


.stock-status--in-stock {
  color: #10b981;
}

.stock-status--out-of-stock {
  color: #dc2626;
}

.stock-status--discontinued {
  color: #9ca3af;
}

.dialog-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.dialog-box {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
  max-height: 80vh;
  overflow-y: auto;

  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.dialog-box h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}


.dialog-box label {
  display: block;
  margin: 10px 0;
  font-weight: 600;
  color: #555;
}

.dialog-box input,
.dialog-box textarea {
  width: 100%;
  padding: 8px;
  margin-top: 5px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.dialog-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.save-button {
  background-color: #4caf50;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.save-button:hover {
  background-color: #43a047;
}

.cancel-button {
  background-color: #f44336;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.cancel-button:hover {
  background-color: #d32f2f;
}

.styled-select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 14px;
  background-color: #f9f9f9;
  color: #333;
  appearance: none;
  outline: none;
  cursor: pointer;
  transition: border-color 0.3s, box-shadow 0.3s;
}

.styled-select:hover {
  border-color: #b3b3b3;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.styled-select:focus {
  border-color: #0284c7;
  box-shadow: 0 0 6px rgba(2, 132, 199, 0.6);
}

.styled-select option {
  padding: 10px;
}

.select-in-stock {
  background-color: #d1fae5;
  color: #065f46;
}

.select-out-of-stock {
  background-color: #fee2e2;
  color: #991b1b;
}

.select-discontinued {
  background-color: #e5e7eb;
  color: #374151;
}

.color-edit-container {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 5px 0;
}

.color-picker {
  width: 40px;
  height: 40px;
  border: none;
  cursor: pointer;
}

.color-code {
  font-size: 14px;
  color: #374151;
}

.remove-color, .add-color {
  background-color: #f44336;
  color: white;
  border: none;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: pointer;
}

.add-color {
  background-color: #4CAF50;
}

.remove-color:hover {
  background-color: #d32f2f;
}

.add-color:hover {
  background-color: #43a047;
}

.color-tag {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 5px;
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

.color-code {
  font-size: 0.9rem;
  font-weight: 500;
  color: #555;
  font-family: 'Inter', sans-serif;
}

.inventory-table {
  width: 100%;
  border-collapse: collapse;
}

.inventory-table th,
.inventory-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}

.inventory-table th {
  background-color: #f3f4f6;
  color: #374151;
}

.color-box {
  display: inline-block;
  width: 20px;
  height: 20px;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-left: 8px;
}

.status-in-stock {
  color: #10b981;
}

.status-low-stock {
  color: #fbbf24;
}

.status-out-of-stock {
  color: #ef4444;
}

.category-edit-container {
  display: flex;
  gap: 10px;
  align-items: center;
}

.tag-input {
  flex: 1;
  padding: 6px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.remove-category {
  background-color: #f44336;
  color: white;
  border: none;
  padding: 6px 10px;
  border-radius: 4px;
  cursor: pointer;
}

.remove-category:hover {
  background-color: #d32f2f;
}

.add-category {
  background-color: #4caf50;
  color: white;
  border: none;
  padding: 6px 10px;
  border-radius: 4px;
  cursor: pointer;
}

.add-category:hover {
  background-color: #43a047;
}

.upload-input {
  display: block;
  margin-top: 10px;
  font-size: 14px;
}

.editable-image {
  max-width: 100%;
  height: auto;
  cursor: pointer;
  border-radius: 8px;
  margin-bottom: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s;
}

.editable-image:hover {
  transform: scale(1.05);
}

</style>