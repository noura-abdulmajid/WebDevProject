<template>
  <div>
    <header>
      <div class="logo-container">
          <img src="/logo.png" class="logo" alt="company logo" />
      </div>
    </header>
  <div class ="main-container">
    <div class="form-container">
      <div class="wrapper">
        <h1>Contact Us</h1>
        <form @submit.prevent="submitForm">
          <div class="contact_section">
            <div class="split">
              <div class="form-split">
                <label for="first_name">First Name <span class="required">*</span></label>
                <input type="text" id="first_name" v-model="formData.firstName" required />
              </div>
              <div class="form-split">
                <label for="last_name">Last Name <span class="required">*</span></label>
                <input type="text" id="last_name" v-model="formData.lastName" required />
              </div>
            </div>
          </div>
          <div class="contact_section">
            <label for="email">Email Address<span class="required">*</span></label>
            <input
              type="email"
              id="email"
              v-model="formData.email"
              @input="validateEmail"
              required
            />
            <label v-if="emailError" class="error-message">{{ emailError }}</label>
          </div>
          <div class="contact_section">
            <label for="message">Message<span class="required">*</span></label>
            <textarea id="message" v-model="formData.message" rows="10" required></textarea>
          </div>
          <button type="submit">Submit</button>
        </form>
      </div>
    </div>
    <div class="map-address-container">
      <div class="map-container">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.162522269119!2d-1.8908227230742072!3d52.48671703869304!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4870bc9ae4f2e4b3%3A0x9a670ba18e08a084!2sAston%20University!5e0!3m2!1sen!2sus!4v1739296581148!5m2!1sen!2sus"
          width="600"
          height="450"
          style="border: 0"
          allowfullscreen
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
        ></iframe>
      </div>
      <div class="address-section">
        <h2>Our Location</h2>
        <p>Aston University</p>
        <p>Aston St, Birmingham</p>
        <p>B4 7ET, United Kingdom</p>
        <p>Phone: +44 (0)121 204 3000</p>
        <p>Email: info@aston.ac.uk</p>
      </div>
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
message: string
}

export default defineComponent({
setup() {
  const formData = reactive<FormData>({
    firstName: '',
    lastName: '',
    email: '',
    message: '',
  })

  const emailError = ref<string>('')

  const validateEmail = () => {
    if (!formData.email.includes('@')) {
      emailError.value = 'Email must contain an @ symbol.'
    } else {
      emailError.value = ''
    }
  }

  const submitForm = () => {
    console.log('Form submitted:', formData)
  }

  return {
    formData,
    emailError,
    validateEmail,
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

.wrapper {
background-color: #d3c6b2;
border-radius: 12px;
padding: 40px;
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
background-color: #000;
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
background-color:#007bff;
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


.map-address-container {
flex: 1;
display: flex;
flex-direction: column;
gap: 20px;
}

.map-container {
flex: 1;
height: 500px;
}

.map-container iframe {
width: 100%;
height: 100%;
border: 0;
border-radius: 12px;
}

.address-section {
background-color: #f0f8ff;
padding: 20px;
border-radius: 12px;
box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.address-section h2 {
font-size: 1.8rem;
margin-bottom: 20px;
color: #000;
}

.address-section p {
margin: 15px 0;
line-height: 1.6;
color: #333;
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

.map-address-container {
  gap: 20px;
}
}
</style>
