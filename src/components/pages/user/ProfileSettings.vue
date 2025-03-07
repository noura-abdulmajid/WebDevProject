<template>
  <main class="profile-settings">
    <div class="main-content">
      <ProfileHeader/>
      <section class="profile-card">

        <div class="profile-card-content">
          <ProfileInfo :isEditing="isEditing" @toggle-edit="toggleEditMode"/>
          <PersonalDetailsForm :isEditing="isEditing" @save-profile="saveProfile" ref="profileForm"/>
        </div>
      </section>
    </div>
  </main>
</template>

<script>
import ProfileHeader from "./ProfileHeader.vue";
import ProfileInfo from "./ProfileInfo.vue";
import PersonalDetailsForm from "./PersonalDetailsForm.vue";
import axiosClient from "@/services/axiosClient.js";
import apiConfig from "@/config/apiURL.js";

export default {
  name: "ProfileSettings",
  components: {
    ProfileHeader,
    ProfileInfo,
    PersonalDetailsForm,
  },
  data() {
    return {
      isEditing: false,
      userProfile: {},
    };
  },
  methods: {
    async loadUserProfile() {
      try {
        const response = await axiosClient.get(apiConfig.userProfile.getProfile);
        this.userProfile = response.data;
        this.$refs.profileForm && this.$refs.profileForm.setFormData(this.userProfile);
        console.log("User profile loaded:", response.data);
      } catch (error) {
        console.error("Failed to load user profile:", error);
      }
    },


    async toggleEditMode() {
      if (this.isEditing) {
        await this.saveProfile();
      }
      this.isEditing = !this.isEditing;
    },

    async saveProfile() {
      try {
        const updatedData = this.$refs.profileForm.getFormData();
        const response = await axiosClient.put(apiConfig.userProfile.updateProfile, updatedData,
            {
              'Content-Type': 'application/json'
            }
        );
        if (response.status === 200) {
          alert("Profile updated successfully!");
          await this.loadUserProfile();
        }
        console.log("Profile updated successfully:", response.data);
      } catch (error) {
        console.error("Failed to update profile:", error);
      }
    },
  },
};
</script>

<style scoped>
.profile-settings {
  background-color: rgba(249, 249, 249, 1);
  display: flex;
  justify-content: center;
  padding-right: 0;
  align-items: center;
  gap: 40px;
  overflow: hidden;
  flex-wrap: wrap;
}

.main-content {
  margin-top: auto;
  margin-bottom: auto;
  font-family: Poppins, -apple-system, Roboto, Helvetica, sans-serif;
  flex-grow: 1;
  flex-shrink: 0;
  flex-basis: 0;
  width: 100%;
  max-width: 1000px;
}

.profile-card {
  border-radius: 10px;
  background-color: rgba(255, 255, 255, 1);
  margin-top: 32px;
  padding-bottom: 71px;
}

.profile-banner {
  aspect-ratio: 12.82;
  object-fit: contain;
  object-position: center;
  width: 100%;
}

.profile-card-content {
  margin-top: 32px;
  padding: 0 32px;
}

@media (max-width: 991px) {
  .profile-settings {
    padding-right: 20px;
  }

  .main-content {
    max-width: 100%;
  }

  .profile-banner {
    max-width: 100%;
  }

  .profile-card-content {
    padding: 0 20px;
  }
}
</style>
