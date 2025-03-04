<template>
  <div class="maintenance-page">
    <img src="/image/dashshoe.png" alt="DashShoe Store Logo" class="logo" />
    <h2>Website Under Maintenance</h2>
    <p>We are currently performing system maintenance. Please try again later.</p>
    <p>If you have any questions, please contact customer support.</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      message: 'We are currently performing system maintenance. Please try again later.'
    };
  }
};
</script>

<style>
.maintenance-page {
  text-align: center;
  padding: 50px;
  font-size: 18px;
}

.logo {
  max-width: 200px;
  margin-bottom: 20px;


  background-color: transparent;
  mix-blend-mode: multiply;
}

</style>