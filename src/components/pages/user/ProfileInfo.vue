<template>
  <section class="profile-info">
    <div class="user-details">
      <img src="/image/dashshoe.png" alt="Profile picture" class="profile-picture" />
      <div class="user-text">
        <h2 class="user-name">{{ fullName }}</h2>
        <p class="user-email">{{ emailAddress }}</p>
      </div>
    </div>
    <button class="edit-button" @click="toggleEdit">
      {{ isEditing ? "Save" : "Edit" }}
    </button>
  </section>
</template>

<script>
export default {
  name: "ProfileInfo",
  props: {
    isEditing: Boolean,
  },
  data() {
    return {
      fullName: "User",
      emailAddress: "",
    };
  },
  mounted() {
    const firstName = localStorage.getItem("user_first_name") || "";
    const lastName = localStorage.getItem("user_last_name") || "";
    const emailAddress = localStorage.getItem("user_email") || "";

    this.emailAddress = emailAddress;
    if (firstName && lastName) {
      this.fullName = `${firstName} ${lastName}`;
    } else if (firstName) {
      this.fullName = firstName;
    }
  },
  methods: {
    toggleEdit() {
      this.$emit("toggle-edit");
    },
  },
};
</script>

<style scoped>
.profile-info {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 20px;
}

.profile-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.user-details {
  display: flex;
  align-items: center;
  gap: 20px;
}

.profile-picture {
  aspect-ratio: 1;
  object-fit: contain;
  object-position: center;
  width: 100px;
  border-radius: 50%;
}

.user-text {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.user-name {
  font-size: 20px;
  font-weight: 500;
}

.user-email {
  font-size: 16px;
  font-weight: 400;
}

.edit-button {
  border-radius: 8px;
  background-color: rgba(65, 130, 249, 1);
  padding: 10px 32px;
  font-size: 16px;
  color: rgba(255, 255, 255, 1);
  font-weight: 400;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.edit-button:hover {
  background-color: rgba(55, 120, 239, 1);
}

@media (max-width: 991px) {
  .edit-button {
    padding: 10px 20px;
  }
}
</style>
