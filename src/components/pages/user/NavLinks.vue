<template>
  <nav class="navigation">
    <NavItem
        imgSrc="/image/tabler--dashboard.png"
        tooltip="Dashboard"
        to="/customer-dashboard"
        :isActive="$route.path === '/customer-dashboard'"
    />
    <NavItem
        imgSrc="/image/iconoir--user-love.png"
        tooltip="Favorites"
        to="/customer-dashboard/favorites"
        :isActive="$route.path === '/customer-dashboard/favorites'"
    />
    <NavItem
        imgSrc="/image/lets-icons--order.png"
        to="/customer-dashboard/orders-history"
        :isActive="$route.path === '/orders-history'"
    />
  </nav>
</template>

<script>
import NavItem from './NavItem.vue'

export default {
  name: 'NavLinks',
  components: {
    NavItem
  }
}
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
