<template>
  <div
    v-if="notification.show"
    class="fixed w-[300px] right-4 top-4 py-2 px-4 text-white animate-fade-in-down rounded"
    :class="[notification.type === 'success' ? 'bg-emerald-500' : 'bg-red-500']"
  >
    {{ notification.message }}
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";

const store = useStore();

const notification = computed(() => store.state.notification);
</script>

