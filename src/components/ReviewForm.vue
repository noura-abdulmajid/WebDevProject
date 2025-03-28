<template>
  <div>
    <header>
      <div class="logo-container">
          <img src="/logo.png" class="logo" alt="company logo" />
      </div>
    </header>
    <div class="container">
      <!--Container classs which is further divided into sub class of wrapper that contains h2(heading) & form-->
      <div class="wrapper">
        <h2>Review Form</h2>
        <form @submit.prevent="submitForm">
          <div class="form-review">
            <!--Input field(label) for email address with a asetrisk(required) indicator-->
            <label for="review_email">Email Address<span class="required">*</span></label>
            <input type="email" id="review_email" v-model="formData.email" required />
          </div>

          <div class="form-split">
            <label>Rating<span class="required">*</span></label>
            <div class="star-rating">
              <span
                v-for="star in 5"
                :key="star"
                class="star"
                :class="{ active: star <= currentRating }"
                @click="setRating(star)"
              >
                ★
              </span>
            </div>
            <input type="hidden" id="ratingValue" v-model="currentRating" required />
          </div>
          <button type="submit">Submit</button>
        </form>
      </div>
    </div>
  </div>
  </template>
<script lang="ts">
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'ReviewForm',
  setup() {
    const formData = ref({
      email: '',
    })
    const currentRating = ref(0)
    const setRating = (rating: number) => {
      if (currentRating.value === rating) {
        currentRating.value = 0
      } else {
        currentRating.value = rating
      }
    }
    const submitForm = () => {
      console.log('form.submitted:', {
        email: formData.value.email,
        rating: currentRating.value,
      })
    }
    return {
      formData,
      currentRating,
      setRating,
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
text-align: center;
}

.logo-container {
width: 200px;
margin: 0 px;
}

.logo {
margin-left: -1000px;
max-width: 100%;
height: auto;
}

h2 {
font-size: 2.0rem;
margin-bottom: 30px;
color: #000;
text-align: center;
}

.container {
  width: 900px;
  margin: 0 auto;
  padding: 20px;
}

.wrapper {
  background-color: #d3c6b2;
  border-radius: 12px;
  padding: 40px;
}

.form-review,
.form-split{
margin-bottom: 20px;
}

label{
display: block;
margin-bottom: 8px;
font-size: 1.5rem;
color: #000;
}

input[type="email"]{
width: 100%;
padding: 10px;
border: 1px solid #ccc;
border-radius: 8px;
font-size: 1rem;
color: #000;
background-color: #fff;
}

.star-rating {
font-size: 3rem;
cursor: pointer;
}

.star {
color: gray;
transition: color 0.2s;
}

.star.active {
color: gold;
}

.required {
color: #ff4d4d;
font-size: 3rem;
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
background-color: #0056b3;
}

button:active {
background-color: #004080;
}
</style>
