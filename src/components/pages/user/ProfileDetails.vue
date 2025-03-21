<template>
  <div>
    <ProfileInfo :isEditing="isEditing" @toggle-edit="toggleEditMode"/>
    <PersonalDetailsForm :isEditing="isEditing" @save-profile="saveProfile" ref="profileForm"/>
  </div>
</template>

<script>
import ProfileInfo from "@/components/pages/user/ProfileInfo.vue";
import PersonalDetailsForm from "@/components/pages/user/PersonalDetailsForm.vue";
import axiosClient from "@/services/axiosClient.js";
import apiConfig from "@/config/apiURL.js";

export default {
  components: {ProfileInfo, PersonalDetailsForm},
  data() {
    return {
      userProfile: {},
      isEditing: false,
    };
  },
  methods: {
    async toggleEditMode() {
      if (this.isEditing) {
        await this.saveProfile();
      }
      this.isEditing = !this.isEditing;
    },

    async saveProfile() {
      try {
        alert("Saving profile...");
        const updatedData = this.$refs.profileForm.getFormData();
        console.log("Updated data:", updatedData);
        const response = await axiosClient.put(apiConfig.userProfile.updateProfile, updatedData,
            {
              'Content-Type': 'application/json'
            }
        );
        if (response.status === 200) {
          alert("Profile updated successfully!");
          localStorage.setItem("tel_no", updatedData.tel_no);
          localStorage.setItem("user_first_name", updatedData.first_name);
          localStorage.setItem("user_last_name", updatedData.surname);
          localStorage.setItem("shipping_address", updatedData.shipping_address);
          localStorage.setItem("billing_address", updatedData.billing_address);
          await this.loadUserProfile();
        }
        console.log("Profile updated successfully:", response.data);
      } catch (error) {
        console.error("Failed to update profile:", error);
      }
    },
    getFormData() {
      return {
        firstName: this.firstName,
        lastName: this.lastName,
        email: this.emailAddress,
        phone: this.telNo,
        shippingAddress: this.shippingAddress,
        billingAddress: this.billingAddress,
      };
    },
    async loadUserProfile() {
      try {
        if (!localStorage.getItem("user_first_name") || !localStorage.getItem("user_last_name")) {
          const response = await axiosClient.get(apiConfig.userProfile.getProfile, {
            'Content-Type': 'application/json'
          });

          this.userProfile = response.data;
          this.$refs.profileForm && this.$refs.profileForm.setFormData(this.userProfile);

          localStorage.setItem("user_first_name", this.userProfile.firstName);
          localStorage.setItem("user_last_name", this.userProfile.lastName);
          localStorage.setItem("user_email", this.userProfile.email);
          localStorage.setItem("tel_no", this.userProfile.phone);
          localStorage.setItem("shipping_address", this.userProfile.shippingAddress);
          localStorage.setItem("billing_address", this.userProfile.billingAddress);

          console.log("User profile loaded and stored in localStorage:", response.data);
        } else {
          console.log("LocalStorage already contains user profile data.");
        }
      } catch (error) {
        console.error("Failed to load user profile:", error);
      }
    }
  },
};
</script>