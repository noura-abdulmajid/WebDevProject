<template>
  <main class="profile-settings">
    <aside class="sidebar">
      <NavLinks />
    </aside>
    <div class="main-content">
      <ProfileHeader />
      <section class="profile-card">
        <img src="#" alt="Profile banner" class="profile-banner" />
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
  justify-content: center;
  padding-right: 0;
  align-items: center;
  flex-wrap: wrap;
  height: 100vh;
  overflow: hidden;
}

.sidebar {
  width: 100px;
  height: auto;
  flex-shrink: 0;
  background-color: #EDE4DA;
  padding: 16px 0;
}

.main-content {
  margin-top: auto;
  margin-bottom: auto;
  font-family: Poppins, -apple-system, Roboto, Helvetica, sans-serif;
  flex-grow: 1;
  flex-shrink: 0;
  flex-basis: 0;
  border-radius: 20px;
  width: 100%;
  max-width: 1000px;

  padding-top: 80px;
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
