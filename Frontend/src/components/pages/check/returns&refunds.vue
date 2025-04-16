<template>
<div>
<header>
<div class="logo-container">
    <img src="/logo.png" class="logo" alt="company logo" />
</div>
</header>
<div class="main-container">
<div class="returns-refunds options-container">
    <div class="wrapper">
    <h1>Returns and Refunds</h1>
    <h2>
        <div class="textprompt">
            If you are not satisfied with your purchase, you may return it within 30 days for a full refund. Please fill out the form below to initiate your return or refund request.
    </div>
</h2>


    <form @submit.prevent="submitForm">
        <div class="contact_details">
        <div class="split">
            <div class="form-split">
            <label for="order_number">Order Number <span class="required">*</span></label>
            <input type="text" id="order_number" v-model="formData.orderNumber" required />
            </div>
            <div class="form-split">
            <label for="email">Email Address <span class="required">*</span></label>
            <input type="email" id="email" v-model="formData.email" required />
            </div>
        </div>
        </div>
        <div class="return_reason">
        <label for="return_reason">Reason for Return <span class="required">*</span></label>
        <textarea id="return_reason" v-model="formData.returnReason" required></textarea>
        </div>
        <button type="submit">Submit Request</button>
    </form>
    </div>
</div>
<div class="info-panel">
    <h3>Return Policy</h3>
    <div class="policy-details">
    <p>
        - Items must be returned within 30 days of purchase.<br>
        - Items must be in their original condition, unworn, and with all tags attached.<br>
        - Refunds will be processed within 5-7 business days after we receive your return.<br>
        - Shipping costs are non-refundable.<br>
        - For more information, please see our <a href="#">full return policy</a>.
    </p>
    </div>
</div>
</div>
</div>
</template>

<script lang="ts">
import { defineComponent, reactive } from 'vue'

interface FormData {
orderNumber: string
email: string
returnReason: string
}

export default defineComponent({
setup() {
const formData = reactive<FormData>({
orderNumber: '',
email: '',
returnReason: ''
});

const submitForm = () => {
console.log('Return/Refund request submitted:', formData)
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
body {
background-color: #d3c6b2;
box-shadow: none;
margin: 0%;
font-family: 'Roboto', sans-serif;
}

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

.wrapper {
background-color: #d3c6b2;
border-radius: 12px;
padding: 40px;
}

h1 {
font-size: 1.99rem;
color:#000;
margin-bottom: 30px;
text-align: center;
}

h2{
color: #000;
font-size: 0.85rem;
margin-bottom: 20px;
text-align: center;
}

h3 {
font-size: 1.99rem;
margin-bottom: 30px;
color:#000;
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
background-color: #fff;
margin-bottom: 20px;
border: 1px solid #fff;
border-radius: 8px;
font-size: 1rem;
}

textarea {
resize: vertical;
min-height: 150px;
color: #000;
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
background-color: blue;
}

.required {
color: #ff4d4d;
font-size: 0.9rem;
}

.info-panel {
background-color: #d3c6b2;
border-radius: 12px;
padding: 20px;
margin-bottom: 20px;
}

.info-panel h3 {
font-size: 1.5rem;
color: #333;
margin-bottom: 15px;
}

.info-panel .policy-details {
font-size: 1rem;
color: #333;
}

.info-panel .policy-details a {
color: #007bff;
text-decoration: none;
}

.info-panel .policy-details a:hover {
text-decoration: underline;
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