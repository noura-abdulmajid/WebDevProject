<template>
  <div>
    <header>
      <div class="logo">
        <img src="/src/image/dashshoe.png" alt="Logo" />
      </div>
    </header>

    <div class="container">
      <div class="wrapper">
        <h2>Review Form</h2>

        <form @submit.prevent="submitForm">
          <div class="form-review">
            <label for="review_email">
              Email Address<span class="required">*</span>
            </label>
            <input
                type="email"
                id="review_email"
                v-model="reviewEmail"
                required
            />
          </div>
          <div class="form-review">
            <label for="review">
              Review<span class="required">*</span>
            </label>
            <textarea
                id="review"
                name="review"
                v-model="reviewText"
                rows="5"
                required
            ></textarea>
          </div>

          <div class="form-split">
            <label>Rating<span class="required">*</span></label>
            <div class="star-rating">
              <span
                  v-for="star in 5"
                  :key="star"
                  class="star"
                  :data-value="star"
                  :style="{ color: star <= rating ? 'gold' : 'gray' }"
                  @click="setRating(star)"
              >
                &#9733;
              </span>
            </div>
          </div>

          <button type="submit">Submit</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import SiteReview from "../scripts/SiteReviewScript.js";

export default SiteReview;
</script>

<style scoped src="@/styles/SiteReviewStyle.css"></style>
