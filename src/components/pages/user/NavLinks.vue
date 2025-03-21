<template>
  <nav class="navigation">
    <NavItem
        v-for="item in navItems"
        :key="item.to"
        :imgSrc="item.imgSrc"
        :tooltip="item.tooltip"
        :to="item.to"
        :isActive="isActive(item.to)"
    />
  </nav>
</template>

<script>
import {computed} from 'vue';
import {useRoute} from 'vue-router';
import NavItem from './NavItem.vue';
import apiURL from '@/config/apiURL';


export default {
  name: 'Navigation',
  components: {
    NavItem,
  },
  setup() {
    const route = useRoute();

    const navItems = computed(() => [
      {
        imgSrc: '/image/tabler--dashboard.png',
        tooltip: 'Dashboard',
        to: apiURL.userProfile.profile,
      },
      {
        imgSrc: '/image/iconoir--user-love.png',
        tooltip: 'Favorites',
        to: apiURL.userProfile.favorites,
      },
      {
        imgSrc: '/image/lets-icons--order.png',
        tooltip: 'Orders History',
        to: apiURL.userProfile.ordersHistory,
      },
    ]);

    const isActive = (path) => route.path === path;

    return {
      navItems,
      isActive,
    };
  },
};
</script>

<style scoped>
.navigation {
  display: flex;
  flex-direction: column;
  gap: 40px;
  padding: 16px 0;
  flex-grow: 1;
  width: 100%;
}

@media (max-width: 640px) {
  .navigation {
    flex-direction: row;
    padding: 0;
    justify-content: space-around;
  }
}
</style>