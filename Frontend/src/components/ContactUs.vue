<template>
  <div class="contact-container">
    <header>
      <div class="contact-logo">
        <img src="/src/image/dashshoe.png" alt="Logo">
      </div>
    </header>
    <div class="contact-wrapper">
      <div class="contact-header">
        <h1>Contact Us</h1>
      </div>

      <form @submit.prevent="submitForm">
        <div class="contact-section">
          <div class="contact-split">
            <div class="contact-form-split">
              <label for="first_name" class="contact-label">First Name <span class="contact-required">*</span></label>
              <input type="text" id="first_name" v-model="firstName" class="contact-input" required>
            </div>
            <div class="contact-form-split">
              <label for="last_name" class="contact-label">Last Name <span class="contact-required">*</span></label>
              <input type="text" id="last_name" v-model="lastName" class="contact-input" required>
            </div>
          </div>

          <div class="contact-section">
            <label for="email" class="contact-label">Email Address<span class="contact-required">*</span></label>
            <input type="email" id="email" v-model="email" class="contact-input" required>
          </div>

          <div class="contact-section">
            <label for="message" class="contact-label">Message<span class="contact-required">*</span></label>
            <textarea id="message" v-model="message" class="contact-textarea" rows="10" required></textarea>
          </div>

          <button type="submit" class="contact-button">Submit</button>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import ContactUsScript from "../scripts/ContactUsScript.js";

export default ContactUsScript;
</script>
<style scoped src="@/styles/ContactUsStyle.css"></style>
