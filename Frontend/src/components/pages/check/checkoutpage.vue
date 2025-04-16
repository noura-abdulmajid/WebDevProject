<template>
    <div>
      <header>
        <div class="logo-container">
            <img src="/logo.png" class="logo" alt="company logo" />
        </div>
      </header>
    <div class ="main-container">
      <div class="delivery options-container">
        <div class="wrapper">
          <h1>Please Fill In The Details To Checkout</h1>
          <form @submit.prevent="submitForm">
            <div class="contact_details">
              <div class="split">
                <div class="form-split">
                  <label for="first_name">First Name <span class="required">*</span></label>
                  <input type="text" id="first_name" v-model="formData.firstName" required />
                </div>
                <div class="form-split">
                  <label for="last_name">Last Name <span class="required">*</span></label>
                  <input type="text" id="last_name" v-model="formData.lastName" required />
                </div>
                <div class="form-split">
                    <label for="contact_number">Contact Number<span class="required">*</span></label>
                    <input type="tel" id="contact_number" v-model="formData.contactNumber" required />
                </div>
                <div class="form-split">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" v-model="formData.email" required />
              </div>
            </div>
          </div>
            <div class="delivery_address">
                <div class="split">
                    <div class="form-split">
                           <label for="country">Country <span class="required">*</span></label>
                           <input type="text" id="country" v-model="formData.country" required />
                    </div>
                    <div class="form-split">
                           <label for="post code">Post Code<span class="required">*</span></label>
                           <input type="text" id="postcode" v-model="formData.postCode" required />
                </div>
            </div> 
            </div>
            <button type="submit">Submit</button>
          </form>
        </div>
      </div>
    </div>
<div class="checkout-panel">
        <h3>Order Summary</h3>
        <div class="summary-item">
        <button class="checkout-button">Checkout Securely</button>
        <div class="payment-options">
          <p>Or</p>
          <button class="payment-button paypal">PayPal Express</button>
          <button class="payment-button clearpay">Clearpay Express</button>
          <button class="payment-button paypal-later">PayPal Pay Later</button>
        </div>
        <div class="discount-section">
          <input type="text" placeholder="Add Discount / Promo Code" />
          <input type="text" placeholder="Add Gift Card" />
        </div>
        <p class="privacy-policy">
          We will use your information in accordance with our
          <a href="#">Privacy Policy</a>. Updated January 2025.
        </p>
      </div>
    </div>
  </div>

</template>
 
  
  <script lang="ts">
  import { defineComponent, reactive, ref } from 'vue'
  
interface FormData {
  firstName: string
  lastName: string
  email: string
  contactNumber: string
  country: string
  postCode: String
}

export default defineComponent({
  setup() {
    const formData = reactive<FormData>({
      firstName: '',
      lastName: '',
      email: '',
      contactNumber: '',
      country: '',
      postCode: ''
    });



    const submitForm = () => {
      console.log('Form submitted:', formData)
    }

    return {
      formData,
            submitForm,
    }
  },
})
</script>

<style>
body {
  background-color: #d3c6b2;
  box-shadow: none;
  margin: 0%;
  font-family: 'Roboto', sans-serif;
}
</style>

<style scoped>

header {
  padding: 20px;
  background-color: #d3c6b2;
}

.logo-container {
  width: 200px;
  padding: 20px;
}

.logo {
  margin-left: -700px;
  max-width: 200px;
  height: auto;
}

.main-container {
  width: 1000px;
  margin: 0 auto;
  padding: 20px;
  display: flex;
  gap: 30px;
  height: auto;
}

.form-container {
  flex: 1;
}
label {
  display: block;
  margin-bottom: 2px;
  font-size: 1rem;
  color: #000;
}

.wrapper {
  background-color: #d3c6b2;
  border-radius: 12px;
  padding: 40px;
}

h1 {
  font-size: 1.99rem;
  margin-bottom: 30px;
  color:dimgrey;
  text-align: center;
}

h3{
    font-size: 1.99rem;
  margin-bottom: 30px;
  color:dimgrey;
  text-align: center;
}
.split {
  display: flex;
  gap: 40px;
  margin-bottom: 20px;
}

.form-split {
  flex: 1;
}

label {
  display: block;
  margin-bottom: 2px;
  font-size: 1rem;
  color: #000;
}

input[type='text'],
input[type='email'],
input[type='number'],
input[type='tel'],
textarea {
  width: 100%;
  padding: 12px;
  margin-bottom: 20px;
  border: 1px solid #fff;
  border-radius: 8px;
  font-size: 1rem; 
}

#first_name,
#last_name,
#contact_number,
#email,
#country,
#postcode {
  background-color: #f0f8ff;
  border: 1px solid #333;
  color: #333; 
}

input[type='text']:focus,
input[type='email']:focus,
textarea:focus {
  border-color:darkslategray;
  box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
  outline: none;
  
}

textarea {
  resize: vertical;
  min-height: 150px;
  color: #fff;
}



button {
  background-color: #000;
  color: #fff;
  padding: 14px 28px;
  font-size: 1.22rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  width: 100%;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color:blue
}

button:active {
  background-color:blue
}

.required {
  color: #ff4d4d;
  font-size: 0.9rem;
}

.error-message {
  color: #ff4d4d;
  font-size: 0.9rem;
  margin-top: 5px;
}

@media (max-width: 768px) {
  .main-container {
    flex-direction: column;
  }

  .split {
    flex-direction: column;
    gap: 10px;
  }

  .wrapper {
    padding: 20px;
  }

  h1 {
    font-size: 2rem;
  }

  input[type='text'],
  input[type='email'],
  textarea {
    font-size: 0.9rem;
  }

  button {
    font-size: 0.9rem;
  }

}
.summary-item {
  background-color: #d3c6b2;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 20px;
}

.summary-item h3 {
  font-size: 1.5rem;
  color: #333;
  margin-bottom: 15px;
}

.summary-item .discount-section {
  margin-bottom: 20px;
}

.summary-item .discount-section input {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  background-color: #fff;
}

.summary-item .checkout-button {
  background-color: #000;
  color: #fff;
  padding: 14px 28px;
  font-size: 1.22rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  width: 100%;
  transition: background-color 0.3s ease;
  margin-bottom: 15px;
}

.summary-item .checkout-button:hover {
  background-color: #0056b3;
}

.summary-item .payment-options {
  text-align: center;
  margin-bottom: 15px;
}

.summary-item .payment-options p {
  margin: 10px 0;
  color: #666;
}

.summary-item .payment-options .payment-button {
  background-color: #fff;
  color: #007bff;
  padding: 10px 20px;
  font-size: 1rem;
  border: 1px solid #007bff;
  border-radius: 8px;
  cursor: pointer;
  margin: 5px;
  transition: background-color 0.3s ease;
}

.summary-item .payment-options .payment-button:hover {
  background-color: #007bff;
  color: #fff;
}

.summary-item .privacy-policy {
  font-size: 0.9rem;
  color: #666;
  text-align: center;
}

.summary-item .privacy-policy a {
  color: #007bff;
  text-decoration: none;
}

.summary-item .privacy-policy a:hover {
  text-decoration: underline;
}
</style>
  