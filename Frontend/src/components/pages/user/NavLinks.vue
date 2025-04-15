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
        tooltip: 'Profile',
        to: '/customer-dashboard',
      },
      {
        imgSrc: '/image/iconoir--user-love.png',
        tooltip: 'Favorites',
        to: '/customer-dashboard/favorites',
      },
      {
        imgSrc: '/image/lets-icons--order.png',
        tooltip: 'Orders',
        to: '/customer-dashboard/orders',
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