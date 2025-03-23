<template>
  <div class="dashboard-container">
    <div class="dashboard-content">
      <h1 class="dashboard-title">Site Reviews Dashboard</h1>
      <!-- Stats Grid -->
      <div class="stats-grid">
        <div v-for="(stat, index) in statsData" :key="index" class="stat-card">
          <div class="stat-content">
            <div class="stat-icon">
              <svg v-if="stat.icon === 'eye'" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
              </svg>
              <svg v-else width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
              </svg>
            </div>
            <div>
              <p class="stat-label">{{ stat.title }}</p>
              <p class="stat-value">{{ computedStats[stat.value] }}</p>
            </div>
          </div>
        </div>
      </div>
      <!-- Category Filter -->
      <div class="category-filter">
        <button
            v-for="category in categories"
            :key="category.value"
            class="category-button"
            :class="{ active: selectedCategory === category.value }"
            @click="selectedCategory = category.value; currentPage = 1"
        >
          {{ category.label }}
        </button>
      </div>
      <!-- Status Message -->
      <div v-if="loading" class="status-message">
        Loading reviews...
      </div>
      <div v-if="!loading && filteredReviews.length === 0" class="status-message">
        No reviews available.
      </div>
      <!-- Scrollable Reviews List -->
      <div class="review-list-container">
        <div class="reviews-grid">
          <article v-for="review in paginatedReviews" :key="review.SR_ID" class="review-card">
            <div class="review-header">
              <div>
                <h2 class="review-title">{{ review.title }}</h2>
                <p class="review-date">{{ formatDate(review.date) }}</p>
              </div>
              <div class="status-badges">
                <span
                    class="status-badge"
                    :class="review.is_read ? 'read' : 'unread'"
                    @click="toggleMarkRead(review)"
                >
                  {{ review.is_read ? 'Read' : 'Unread' }}
                </span>
                <span
                    class="status-badge"
                    :class="review.is_replied ? 'replied' : 'unreplied'"
                    @click="toggleMarkReplied(review)"
                >
                  {{ review.is_replied ? 'Replied' : 'Unreplied' }}
                </span>
              </div>
            </div>
            <p class="review-content">{{ review.content }}</p>
            <div v-if="review.is_replied" class="review-reply">
              <p class="review-reply-text">{{ review.reply }}</p>
            </div>
            <button v-if="!review.is_replied" class="reply-button" @click="review.showReply = !review.showReply">
              Reply to review
            </button>
            <div v-if="review.showReply" class="reply-form">
              <textarea
                  v-model="review.replyText"
                  class="reply-textarea"
                  placeholder="Write your reply..."
                  rows="4"
              ></textarea>
              <button class="send-button" @click="sendReply(review)">Send Reply</button>
            </div>
          </article>
        </div>
      </div>
      <!-- Pagination Controls -->
      <div v-if="totalPages > 1" class="pagination">
        <button class="page-btn" @click="prevPage" :disabled="currentPage === 1">Previous</button>
        <span class="page-info">{{ currentPage }} / {{ totalPages }}</span>
        <button class="page-btn" @click="nextPage" :disabled="currentPage === totalPages">Next</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref, computed, onMounted} from "vue";
import axios from "axios";

const reviews = ref([]);
const stats = ref(null);
const loading = ref(true);
const selectedCategory = ref("all");
const currentPage = ref(1);
const itemsPerPage = ref(5);

const computedStats = computed(() => ({
  total_read: reviews.value.filter((r) => r.is_read).length,
  total_unread: reviews.value.filter((r) => !r.is_read).length,
  total_replied: reviews.value.filter((r) => r.is_replied).length,
  total_unreplied: reviews.value.filter((r) => !r.is_replied).length,
}));

const categories = [
  {label: "All", value: "all"},
  {label: "Read", value: "read"},
  {label: "Unread", value: "unread"},
  {label: "Replied", value: "replied"},
  {label: "Unreplied", value: "unreplied"}
];

const statsData = [
  {title: "Total Read", value: "total_read", icon: "eye"},
  {title: "Total Unread", value: "total_unread", icon: "eye"},
  {title: "Total Replied", value: "total_replied", icon: "message"},
  {title: "Total Unreplied", value: "total_unreplied", icon: "message"}
];

const filteredReviews = computed(() => {
  if (selectedCategory.value === "all") return reviews.value;
  if (selectedCategory.value === "read")
    return reviews.value.filter((r) => r.is_read);
  if (selectedCategory.value === "unread")
    return reviews.value.filter((r) => !r.is_read);
  if (selectedCategory.value === "replied")
    return reviews.value.filter((r) => r.is_replied);
  return reviews.value.filter((r) => !r.is_replied);
});

const totalPages = computed(() => Math.ceil(filteredReviews.value.length / itemsPerPage.value));

const paginatedReviews = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  return filteredReviews.value.slice(start, start + itemsPerPage.value);
});

const formatDate = (dateString) => {
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
    hour12: false
  };
  return new Date(dateString).toLocaleDateString("en-GB", options);
};

const sendReply = async (review) => {
  if (!review.replyText) {
    alert("Please enter a reply message.");
    return;
  }

  const originalReply = review.reply;
  review.showReply = false;
  review.is_replied = true;
  review.reply = review.replyText;
  review.is_read = true;

  try {
    // Call the backend API to update the review
    await axios.patch(`http://127.0.0.1:8000/api/DashShoe/admin/site-reviews/reply/${review.id}`, {
      reply: review.replyText,
      is_replied: true,
      is_read: true,
    }, {
      headers: {
        Authorization: `${localStorage.getItem("token_type")} ${localStorage.getItem("jwt")}`
      }
    });
    // If the API call is successful, nothing further is needed.
  } catch (error) {
    alert("Failed to sync the reply with the backend. Please try again later.");
    // Rollback the optimistic update if the API call fails.
    review.is_replied = false;
    review.is_read = false;
    review.reply = originalReply;
  } finally {
    review.replyText = "";
  }
};

const toggleMarkRead = async (review) => {
  const newState = !review.is_read;
  try {
    await axios.put(
        `http://127.0.0.1:8000/api/DashShoe/admin/site-reviews/${review.id}/mark-as-read`,
        { is_read: newState },
        {
          headers: {
            Authorization: `${localStorage.getItem("token_type")} ${localStorage.getItem("jwt")}`
          }
        }
    );
    review.is_read = newState;
  } catch (error) {
    alert("Failed to toggle review read state");
  }
};

const toggleMarkReplied = async (review) => {
  const newState = !review.is_replied;
  try {
    await axios.put(
        `http://127.0.0.1:8000/api/DashShoe/admin/site-reviews/${review.id}/mark-as-replied`,
        { is_replied: newState },
        {
          headers: {
            Authorization: `${localStorage.getItem("token_type")} ${localStorage.getItem("jwt")}`
          }
        }
    );
    review.is_replied = newState;
  } catch (error) {
    alert("Failed to toggle review replied state");
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const fetchReviews = async () => {
  loading.value = true;
  try {
    const response = await axios.get(
        "http://127.0.0.1:8000/api/DashShoe/admin/site_review",
        {
          headers: {
            Authorization: `${localStorage.getItem("token_type")} ${localStorage.getItem("jwt")}`
          }
        }
    );
    if (response.data.success) {
      reviews.value = response.data.data.map((r) => ({
        ...r,
        id: r.SR_ID,
        title: r.review_email,
        date: r.created_at,
        content: r.review,
        reply: r.reply || "",
        showReply: false,
        replyText: ""
      }));
      stats.value = response.data.statistics;
    } else {
      reviews.value = [];
      stats.value = null;
    }
  } catch (error) {
    alert("Failed to load reviews. Please try again later.");
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchReviews();
});
</script>

<style>
.dashboard-container {
  min-height: 100vh;
  width: 100%;
  background-color: #F4F7FE;
  font-family: 'Inter', sans-serif;
  padding: 32px;
}

.dashboard-content {
  max-width: 1200px;
  margin: 0 auto;
}

.dashboard-title {
  font-size: 32px;
  font-weight: bold;
  color: #2B3674;
  margin-bottom: 32px;
  text-align: center;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
  margin-bottom: 32px;
}

@media (max-width: 640px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
}

.stat-card {
  background-color: white;
  border-radius: 12px;
  padding: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-content {
  display: flex;
  align-items: center;
  gap: 16px;
}

.stat-icon {
  width: 30px;
  height: 30px;
  border-radius: 8px;
  background-color: #E9EDF7;
  display: flex;
  align-items: center;
  justify-content: center;
}

.stat-icon svg {
  width: 24px;
  height: 24px;
}

.stat-icon path {
  fill: #4318FF;
}

.stat-label {
  font-size: 12px;
  color: #A3AED0;
}

.stat-value {
  font-size: 20px;
  font-weight: bold;
  color: #2B3674;
}

.category-filter {
  display: flex;
  justify-content: center;
  gap: 12px;
  margin-bottom: 32px;
  flex-wrap: wrap;
}

.category-button {
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.3s ease;
  background-color: white;
  color: #2B3674;
  border: none;
  cursor: pointer;
}

.category-button.active {
  background-color: #4318FF;
  color: white;
}

.category-button:not(.active):hover {
  background-color: #E9EDF7;
}

.status-message {
  text-align: center;
  font-size: 16px;
  color: #2B3674;
  margin-bottom: 16px;
}

.review-list-container {
  max-height: 400px;
  overflow-y: auto;
  margin-bottom: 16px;
}

.reviews-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
}

.review-card {
  background-color: white;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 16px;
  margin-bottom: 16px;
}

.review-title {
  font-size: 18px;
  font-weight: bold;
  color: #2B3674;
  margin-bottom: 8px;
}

.review-date {
  font-size: 14px;
  color: #A3AED0;
}

.status-badges {
  display: flex;
  gap: 8px;
}

.status-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.status-badge.read {
  background-color: #E9EDF7;
  color: #4318FF;
}

.status-badge.unread {
  background-color: #FFE7E7;
  color: #FF0000;
}

.status-badge.replied {
  background-color: #E9EDF7;
  color: #4318FF;
}

.status-badge.unreplied {
  background-color: #FFE7E7;
  color: #FF0000;
}

.review-content {
  font-size: 16px;
  color: #2B3674;
  margin-bottom: 16px;
}

.review-reply {
  background-color: #F4F7FE;
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 16px;
}

.review-reply-text {
  font-size: 14px;
  color: #2B3674;
}

.reply-button {
  font-size: 14px;
  color: #4318FF;
  font-weight: 500;
  background: none;
  border: none;
  cursor: pointer;
}

.reply-form {
  margin-top: 16px;
}

.reply-textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #E9EDF7;
  border-radius: 8px;
  margin-bottom: 12px;
  font-size: 14px;
  resize: vertical;
}

.send-button {
  padding: 8px 16px;
  background-color: #4318FF;
  color: white;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.send-button:hover {
  background-color: #3614CC;
}

.pagination {
  position: sticky;
  bottom: 0;
  z-index: 10;
  background-color: #F4F7FE;
  padding: 16px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 16px;
  margin-top: 16px;
}

.page-btn {
  padding: 8px 16px;
  background-color: #4318FF;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.page-btn:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.page-info {
  font-size: 16px;
  color: #2B3674;
}
</style>
