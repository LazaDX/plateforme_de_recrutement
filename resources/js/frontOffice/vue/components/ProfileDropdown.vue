<template>
  <div
    class="relative inline-block"
    @mouseenter="showMenu"
    @mouseleave="hideMenu"
  >
    <button
      class="flex items-center space-x-2 p-2 border rounded-2xl cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500"
      @click="toggleMenu"
    >
      <slot name="button"></slot>
      <svg
        class="h-4 w-4 text-gray-600 hidden md:block transition-transform"
        :class="{ 'rotate-180': isOpen }"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M19 9l-7 7-7-7"
        />
      </svg>
    </button>
    <div
      v-show="isOpen"
      class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-100 transition-opacity duration-300"
      @mouseenter="cancelHide"
      @mouseleave="hideMenu"
    >
      <slot name="menu"></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProfileDropdown',
  data() {
    return {
      isOpen: false,
      hideTimeout: null,
    };
  },
  methods: {
    showMenu() {
      clearTimeout(this.hideTimeout);
      this.isOpen = true;
    },
    hideMenu() {
      this.hideTimeout = setTimeout(() => {
        this.isOpen = false;
      }, 200);
    },
    cancelHide() {
      clearTimeout(this.hideTimeout);
    },
    toggleMenu() {
      this.isOpen = !this.isOpen;
    },
  },
  beforeDestroy() {
    clearTimeout(this.hideTimeout);
  },
};
</script>
