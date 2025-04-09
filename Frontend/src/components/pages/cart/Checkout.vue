<template>
  <div class="checkout-container">

    <header class="checkout"></header>

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
            P_ID: item.P_ID,
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
          localStorage.removeItem("cart");
          window.dispatchEvent(new Event("cart-updated"));
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
      this.cart = JSON.parse(localStorage.getItem("cart") || "[]");
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

.checkout-container {
  padding: 60px;

  font-family: 'Inter', sans-serif;
}

.content {
  display: flex;
  gap: 40px;
  max-width: 1200px;
  margin: auto;
  margin-top: 40px;
}


.checkout-form {
  flex: 2;
  background: #fff;
  padding: 30px 40px;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
}

.checkout-form h2 {
  font-size: 24px;
  font-weight: 600;
  text-align: center;
  color: #333;
  margin-bottom: 30px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 6px;
  color: #555;
}

.form-group input {
  width: 100%;
  padding: 12px 14px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #fafafa;
  transition: border-color 0.2s;
}

.form-group input:focus {
  border-color: #007bff;
  outline: none;
  background-color: #fff;
}

.form-row {
  display: flex;
  gap: 20px;
}

.form-row .form-group {
  flex: 1;
}

.submit-button {
  width: 100%;
  padding: 14px;
  background-color: #28a745;
  color: #fff;
  border: none;
  font-size: 16px;
  border-radius: 8px;
  font-weight: 600;
  margin-top: 20px;
  transition: background 0.3s;
}

.submit-button:hover {
  background-color: #218838;
}

.back-button {
  width: 100%;
  padding: 14px;
  margin-top: 15px;
  background-color: #dc3545;
  color: white;
  font-size: 16px;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  transition: background 0.3s;
}

.back-button:hover {
  background-color: #c82333;
}

.summary-section {
  flex: 1;
  background: #ffffff;
  padding: 30px 25px;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
  min-width: 280px;
}

.summary-section h2 {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 20px;
  color: #333;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  font-size: 14px;
  margin-bottom: 12px;
  color: #444;
}

.summary-total {
  margin-top: 30px;
  border-top: 1px solid #eee;
  padding-top: 15px;
}

.summary-total p {
  font-size: 15px;
  font-weight: 500;
  margin: 5px 0;
  color: #222;
}

.empty-cart {
  font-size: 16px;
  text-align: center;
  font-weight: 500;
  color: #999;
  padding: 40px 0;
}

</style>