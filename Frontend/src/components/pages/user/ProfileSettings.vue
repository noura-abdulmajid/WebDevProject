<template>
  <main class="profile-settings">
    <aside class="sidebar">
      <NavLinks />
    </aside>
    <div class="main-content">
      <ProfileHeader />
      <section class="profile-card">
        <img src="/image/userheader.png" alt="Profile banner" class="profile-banner" />
        <div class="profile-card-content">
          <router-view />
        </div>
      </section>
    </div>
  </main>
</template>

<script>
import ProfileHeader from "./ProfileHeader.vue";
import ProfileInfo from "./ProfileInfo.vue";
import NavLinks from "./NavLinks.vue";
import PersonalDetailsForm from "./PersonalDetailsForm.vue";

export default {
  name: "ProfileSettings",
  components: {
    ProfileHeader,
    ProfileInfo,
    PersonalDetailsForm,
    NavLinks,
  },
  data() {
    return {
      isEditing: false,
      userProfile: {},
    };
  },
};
</script>

<style scoped>
.profile-settings {
  background-color: #EDE4DA;
  display: flex;
  flex-wrap: wrap;
  min-height: 100vh;
  font-family: "Poppins", sans-serif;
}

.sidebar {
  width: 90px;
  padding: 24px 0;
  background-color: #EDE4DA;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  margin-top: 110px;
  border-right: 1px solid rgba(0, 0, 0, 0.05);
}

.main-content {
  flex: 1;
  padding: 60px 40px 40px;
  max-width: 1000px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
}

.profile-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  margin-top: 32px;
  overflow: hidden;
  transition: all 0.3s ease;
}

.profile-banner {
  width: 100%;
  height: 80px;
  object-fit: cover;
  object-position: center;
}

.profile-card-content {
  padding: 32px;
}

@media (max-width: 991px) {
  .main-content {
    padding: 40px 20px;
  }

  .sidebar {
    width: 100%;
    height: auto;
    justify-content: space-around;
    border-right: none;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  }

  .profile-card-content {
    padding: 24px 16px;
  }
}

@media (max-width: 600px) {
  .profile-banner {
    height: 160px;
  }

  .profile-card-content {
    padding: 20px 12px;
  }
}

</style>
