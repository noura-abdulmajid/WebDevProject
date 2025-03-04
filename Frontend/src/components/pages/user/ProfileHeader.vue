<template>
  <header class="profile-header">
    <div class="welcome-section">
      <h1 class="welcome-title">Welcome, {{ fullName }}</h1>
      <time class="welcome-date">{{ currentDate }}</time>
    </div>
  </header>
</template>

<script>
export default {
  name: "ProfileHeader",
  data() {
    return {
      fullName: "User",
      currentDate: new Date().toLocaleDateString("en-US", {
        weekday: "short",
        day: "2-digit",
        month: "long",
        year: "numeric",
      }),
    };
  },
  mounted() {
    const firstName = localStorage.getItem("user_first_name");
    const lastName = localStorage.getItem("user_last_name");

    if (firstName && lastName) {
      this.fullName = `${firstName} ${lastName}`;
    } else if (firstName) {
      this.fullName = firstName;
    }
  },
};
</script>

<style scoped>
.profile-header {
  display: flex;
  width: 100%;
  align-items: stretch;
  gap: 20px;
  flex-wrap: wrap;
  justify-content: space-between;
}

.welcome-section {
  display: flex;
  flex-direction: column;
  align-items: stretch;
}

.welcome-title {
  color: rgba(62, 67, 93, 1);
  font-size: 24px;
  font-weight: 500;
}

.welcome-date {
  color: rgba(173, 167, 167, 1);
  font-size: 16px;
  font-weight: 400;
  align-self: start;
  margin-top: 12px;
}
</style>
