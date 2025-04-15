<template>
  <div class="tooltip-container">
    <router-link
        :to="to"
        class="nav-button"
        :class="{ 'nav-button--active': isActive }"
    >
      <img v-if="imgSrc" :src="imgSrc" :alt="tooltip" class="nav-img">
      <i v-else :class="['nav-icon', iconClass]" aria-hidden="true"></i>
    </router-link>
    <span class="tooltip-text">{{ tooltip }}</span>
  </div>
</template>

<script>
export default {
  name: "NavItem",
  props: {
    iconClass: {
      type: String,
      default: "",
    },
    imgSrc: {
      type: String,
      default: "",
    },
    tooltip: {
      type: String,
      default: "",
    },
    isActive: {
      type: Boolean,
      default: false,
    },
    to: {
      type: [String, Object],
      required: true,
    }
  },
};
</script>

<style scoped>
.nav-button {
  width: 100%;
  height: 30px;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  color: #6b7280;
  transition: all 0.2s ease;
  background: none;
  border: none;
  padding: 0;
}

.nav-button:hover {
  color: #4b5563;
}

.nav-button--active {
  color: #4b5563;
}

.nav-icon {
  font-size: 20px;
}

.nav-img {
  width: 24px;
  height: 24px;
  object-fit: contain;
}

.tooltip-container {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.tooltip-text {
  visibility: hidden;
  background-color: black;
  color: #fff;
  text-align: center;
  padding: 5px 10px;
  border-radius: 5px;
  position: absolute;
  bottom: -30px;
  left: 50%;
  transform: translateX(-50%);
  white-space: nowrap;
  font-size: 12px;
  opacity: 0;
  transition: opacity 0.2s;
}

.tooltip-container:hover .tooltip-text {
  visibility: visible;
  opacity: 1;
}
</style>
