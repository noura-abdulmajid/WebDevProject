<template>
  <div>
    <header>
      <div class="logo-container">
        <img src="/src/image/logo%20three.png" class="logo" alt="Company Logo">
      </div>
    </header>
    <div class="main-container">
      <div class="form-container">
        <div class="wrapper">
          <h1>Contact Us</h1>
          <form @submit.prevent="submitForm">
            <div class="contact-section">
              <div class="split">
                <div class="form-split">
                  <label for="first_name" class="contact-label">First Name <span class="required">*</span></label>
                  <input type="text" id="first_name" v-model="formData.firstName" class="contact-input" required />
                </div>
                <div class="form-split">
                  <label for="last_name" class="contact-label">Last Name <span class="required">*</span></label>
                  <input type="text" id="last_name" v-model="formData.lastName" class="contact-input" required />
                </div>
              </div>
            </div>
            <div class="contact-section">
              <label for="email" class="contact-label">Email Address<span class="required">*</span></label>
              <input type="email" id="email" v-model="formData.email" class="contact-input" @input="validateEmail" required />
              <label v-if="emailError" class="error-message">{{ emailError }}</label>
            </div>
            <div class="contact-section">
              <label for="message" class="contact-label">Message<span class="required">*</span></label>
              <textarea id="message" v-model="formData.message" class="contact-textarea" rows="10" required></textarea>
            </div>
            <button type="submit" class="contact-button">Submit</button>
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
<script>
import ContactUsScript from "../scripts/ContactUsScript.js";

export default ContactUsScript;
</script>
<style scoped src="@/styles/ContactUsStyle.css"></style>
