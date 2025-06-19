<template>
  <transition name="flash-fade-slide">
    <div v-if="show" :class="['flash', type]">
      {{ message }}
    </div>
  </transition>
</template>

<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const show = ref(false);
const message = ref('');
const type = ref('success');

const flash = usePage().props.flash;

watch(
  () => flash,
  (newFlash) => {
    if (newFlash.success || newFlash.error || newFlash.message) {
      message.value = newFlash.success || newFlash.error || newFlash.message;
      type.value = newFlash.error ? 'error' : 'success';
      show.value = true;

      setTimeout(() => {
        show.value = false;
      }, 4000);
    }
  },
  { immediate: true, deep: true }
);
</script>

<style scoped>
.flash {
  position: fixed;
  top: 2rem;
  right: 2rem;
  padding: 1rem 1.5rem;
  border-radius: 5px;
  color: white;
  font-weight: 600;
  z-index: 9999;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  user-select: none;
}
.flash.success {
  background-color: #38a169;
}
.flash.error {
  background-color: #e53e3e;
}

/* Transition classes */
.flash-fade-slide-enter-active,
.flash-fade-slide-leave-active {
  transition: opacity 0.4s ease, transform 0.4s ease;
}
.flash-fade-slide-enter-from,
.flash-fade-slide-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
.flash-fade-slide-enter-to,
.flash-fade-slide-leave-from {
  opacity: 1;
  transform: translateX(0);
}
</style>
