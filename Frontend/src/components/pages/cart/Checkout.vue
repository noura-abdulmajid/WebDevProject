<template>
  <div class="checkout-container">

    <header>
      <div class="logo">
        <img src="/image/logo%20three.png" alt="DASH Shoe Logo"/>
      </div>
      <nav>
        <ul>
          <li class="dropdown-parent">
            <a>HOME</a>
            <ul class="dropdown">
              <li><a href="/public">Home Page</a></li>
            </ul>
          </li>
          <li><a href="#">ABOUT</a></li>
          <li class="dropdown-parent">
            <a href="#">SHOPPING</a>
            <ul class="dropdown">
              <li><a href="/pages/products/WomenCollection">Women's</a></li>
              <li><a href="/pages/products/MenCollection">Men's</a></li>
              <li><a href="/pages/products/ChildrenCollection">Children</a></li>
            </ul>
          </li>
          <li class="dropdown-parent">
            <a>CONTACT</a>
            <ul class="dropdown">
              <li><a href="/contact">Contact Us</a></li>
            </ul>
          </li>
        </ul>
      </nav>
    </header>

    <div class="content">
      <div class="checkout-form">
        <h2>Checkout</h2>

        <form @submit.prevent="handlePayment">
          <div class="form-row">
            <div class="form-group">
              <label for="firstName">First Name</label>
              <input
                  type="text"
                  id="firstName"
                  v-model="firstName"
                  placeholder="Enter your first name"
                  required
              />
            </div>
            <div class="form-group">
              <label for="surname">Surname</label>
              <input
                  type="text"
                  id="surname"
                  v-model="surname"
                  placeholder="Enter your surname"
                  required
              />
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                v-model="email"
                placeholder="Enter your email"
                required
            />
          </div>

          <div class="form-group">
            <label for="cardNumber">Card Number</label>
            <input
                type="text"
                id="cardNumber"
                v-model="cardNumber"
                placeholder="1234 5678 9012 3456"
                required
            />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="expiration">Expiration Date</label>
              <input
                  type="text"
                  id="expiration"
                  v-model="expiration"
                  placeholder="MM/YY"
                  required
              />
            </div>

            <div class="form-group">
              <label for="cvv">CVV</label>
              <input
                  type="text"
                  id="cvv"
                  v-model="cvv"
                  placeholder="123"
                  required
              />
            </div>
          </div>

          <button type="submit" class="submit-button">Pay Now</button>
        </form>

        <button class="back-button" @click="goBack">Continue Shopping</button>
      </div>

      <div class="summary-section">
        <h2>Order Summary</h2>

        <div v-if="cart.length === 0" class="empty-cart">
          Your cart is empty.
        </div>
        <div v-else>
          <div class="summary-item" v-for="(item, index) in cart" :key="index">
            <p>{{ item.name }} (x{{ item.quantity }})</p>
            <p>£{{ (item.price * item.quantity).toFixed(2) }}</p>
          </div>

          <div class="summary-total">
            <p><strong>Total Items:</strong> {{ totalQuantity }}</p>
            <p><strong>Total Price:</strong> £{{ totalPrice.toFixed(2) }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Cookies from "js-cookie";
import axios from "axios";

export default {
  name: "Checkout",
  data() {
    return {
      firstName: "",
      surname: "",
      email: "",
      cardNumber: "",
      expiration: "",
      cvv: "",
      cart: [],
    };
  },
  computed: {
    totalQuantity() {
      return this.cart.reduce((sum, item) => sum + item.quantity, 0);
    },
    totalPrice() {
      return this.cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    },
  },
  methods: {
    async handlePayment() {
      const paymentData = {
        customer: {
          first_name: this.firstName,
          surname: this.surname,
          email: this.email,
        },
        payment: {
          cardNumber: this.cardNumber,
          expiration: this.expiration,
          cvv: this.cvv,
        },
        order: {
          items: this.cart.map(item => ({
            name: item.name,
            quantity: item.quantity,
            price: item.price,
          })),
          totalQuantity: this.totalQuantity,
          totalPrice: this.totalPrice
        }
      };

      try {
        const response = await axios.post(
            "http://127.0.0.1:8000/api/DashShoe/checkout",
            paymentData,
            {
              headers: {
                "Content-Type": "application/json"
              }
            }
        );

        if (response.status === 200) {
          alert("Payment Successful! Thank you for your purchase.");
          Cookies.remove("cart");
          this.$router.push("/MenCollection");
        } else {
          alert("Payment failed. Please try again.");
        }
      } catch (error) {
        console.error("Error during payment:", error);
        alert("An error occurred during the payment process. Please try again.");
      }
    },
    async logCheckoutVisit() {
      try {
        await axios.post(
            "http://127.0.0.1:8000/api/DashShoe/log-visit",
            {
              page: "Checkout-page",
              timestamp: new Date().toISOString(),
            },
            {
              headers: {
                "Content-Type": "application/json",
              },
            }
        );
      } catch (error) {
        console.error("Failed to log user visit:", error);
      }
    },
    loadCart() {
      this.cart = JSON.parse(Cookies.get("cart") || "[]");
    },
    goBack() {
      this.$router.push("/MenCollection");
      this.logCheckoutVisit();
    },
  },
  mounted() {
    this.loadCart();
    this.logCheckoutVisit();
  },
};
</script>
<style scoped>

.content {
  display: flex;
  gap: 20px;
}

.checkout-form {
  flex: 2;
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.checkout-form h2 {
  text-align: center;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-size: 14px;
  font-weight: bold;
  margin-bottom: 5px;
}

.form-group input {
  width: 100%;
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.form-row {
  display: flex;
  gap: 15px;
}

.form-row .form-group {
  flex: 1;
}

.submit-button {
  width: 100%;
  padding: 10px;
  background-color: #4caf50;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  margin-top: 10px;
  cursor: pointer;
}

.submit-button:hover {
  background-color: #45a049;
}

.back-button {
  width: 100%;
  padding: 10px;
  margin-top: 15px;
  background-color: #f44336;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
}

.back-button:hover {
  background-color: #e53935;
}

.summary-section {
  flex: 1;
  background: #f9f9f9;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.summary-section h2 {
  margin-bottom: 15px;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}

.summary-total {
  margin-top: 20px;
}

.empty-cart {
  text-align: center;
  font-size: 16px;
  font-weight: bold;
  color: #999;
}
</style>