<template>
  <div class="site-review">
    <h1 class="title">Site Reviews</h1>

    <!-- Show loading state -->
    <div v-if="loading" class="loading">Loading reviews...</div>

    <!-- Display when no reviews are found -->
    <div v-if="!loading && reviews.length === 0" class="no-reviews">
      <p>No reviews available.</p>
    </div>

    <!-- Display reviews -->
    <div v-else class="reviews">
      <div class="review" v-for="review in reviews" :key="review.SR_ID">
        <p class="review-email"><strong>Email: </strong>{{ review.review_email }}</p>
        <p class="review-text">{{ review.review }}</p>
        <p class="review-rating">
          <strong>Rating: </strong>
          <span v-for="n in 5" :key="n" class="star">
            {{ n <= review.rating ? "★" : "☆" }}
          </span>
        </p>
        <p class="review-date"><strong>Created At: </strong>{{ formatDate(review.created_at) }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios"; // Import Axios for making HTTP requests

export default {
  name: "SiteReview",
  data() {
    return {
      reviews: [], // Array to hold the review data
      loading: false, // State to manage the loading process
    };
  },
  methods: {
    /**
     * Fetch reviews data from the API
     */
    async fetchReviews() {
      this.loading = true; // Set loading state to true
      try {
        // Perform GET request to the API endpoint
        const response = await axios.get("http://127.0.0.1:8000/api/DashShoe/admin/site_review", {
          headers: {
            // Include Authorization header using token stored in localStorage
            Authorization: `${localStorage.getItem("token_type")} ${localStorage.getItem("jwt")}`,
          },
        });

        // Parse the response and set reviews if valid
        if (response.data.success && Array.isArray(response.data.data)) {
          this.reviews = response.data.data;
        } else {
          console.error("Invalid data format:", response.data);
          this.reviews = [];
        }
      } catch (error) {
        // Handle errors during the request
        console.error("Error fetching reviews:", error.message || error);
        alert("Failed to load reviews. Please try again later.");
      } finally {
        // Reset loading state
        this.loading = false;
      }
    },

    /**
     * Format date from ISO string
     * @param {string} dateString
     * @returns {string} - UK English date format
     */
    formatDate(dateString) {
      const options = {
        year: "numeric",   // 2025
        month: "long",     // March
        day: "numeric",    // 12
        hour: "2-digit",   // Hour (with leading zero if needed)
        minute: "2-digit", // Minutes
        hour12: false,     // 24-hour format, change to true for AM/PM
      };
      // Use "en-GB" for UK-style date format
      return new Date(dateString).toLocaleDateString("en-GB", options);
    },
  },
  mounted() {
    // Fetch reviews when the Vue component is mounted
    this.fetchReviews();
  },
};
</script>

<style scoped>
.site-review {
  font-family: "Arial", sans-serif;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  width: 80%;
  margin: 0 auto;
}
.title {
  text-align: center;
  color: #333;
  margin-bottom: 20px;
}

/* Scrollable container for reviews */
.reviews {
  display: flex;
  flex-direction: column;
  gap: 20px;
  max-height: 500px; /* Limit the height of the reviews section */
  overflow-y: auto; /* Enable vertical scrolling */
  padding-right: 10px; /* Add spacing to avoid overlap with scrollbar */
}

/* Styling for individual review cards */
.review {
  background-color: #fff;
  border: 1px solid #ddd;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.review-email {
  color: #555;
  margin-bottom: 10px;
}
.review-text {
  color: #444;
  margin-bottom: 10px;
  font-size: 14px;
}
.review-rating {
  color: #f39c12;
  font-size: 18px;
  margin-bottom: 10px;
}
.review-date {
  color: #888;
  font-size: 12px;
}
.star {
  font-size: 18px;
  margin-right: 1px;
}

/* Loading indicator */
.loading {
  text-align: center;
  color: #555;
}

/* No reviews found message */
.no-reviews {
  text-align: center;
  color: #888;
}
</style>