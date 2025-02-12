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
          <h1>CheckOut</h1>
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
                    <input type="numbers" id="contact_number" v-model="formData.contactNumber" required />
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
                           <label for="address finder">Address Finder<span class="required">*</span></label>
                           <input type="text" id="postcode" v-model="formData.postCode" required />
                </div>
            </div> 
            </div>
            <button type="submit">Submit</button>
          </form>
        </div>
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
          <a href="#">Privacy Policy</a>. Updated January 2023.
        </p>
      </div>
    </div>
</template>
 
  
  <script lang="ts">
  import { defineComponent, reactive, ref } from 'vue'
  
  interface FormData {
    firstName: string
    lastName: string
    contactNumber: string
    country: string
    postCode: String
  }
  
  export default defineComponent({
    setup() {
      const formData = reactive<FormData>({
        firstName: '',
        lastName: '',
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
    background-color: #d4cec5;
    box-shadow: none;
    margin: 0%;
    font-family: 'Roboto', sans-serif;
  }
</style>

<style scoped>

header {
  padding: 20px;
  background-color: #d4cec5;
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

.wrapper {
  background-color: #fff;
  border-radius: 12px;
  padding: 40px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

h1 {
  font-size: 2.5rem;
  margin-bottom: 30px;
  color: #000;
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
textarea {
  width: 100%;
  padding: 12px;
  margin-bottom: 20px;
  border: 1px solid #fff;
  border-radius: 8px;
  font-size: 1rem;
  transition:
    border-color 0.3s ease,
    box-shadow 0.3s ease;
}

#first_name,
#last_name,
#email,
#message {
  background-color: #f0f8ff;
  border: 1px solid #333;
  color: #333; 
}

input[type='text']:focus,
input[type='email']:focus,
textarea:focus {
  border-color: #007bff;
  box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
  outline: none;
  
}

textarea {
  resize: vertical;
  min-height: 150px;
  color: #fff;
}

button {
  background-color: #007bff;
  color: #fff;
  padding: 14px 28px;
  font-size: 1rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  width: 100%;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #0056b3;
}

button:active {
  background-color: #004080;
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
</style>
  