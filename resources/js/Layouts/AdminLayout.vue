<script setup>
import Sidenav from "../Shared/Layouts/Sidenav.vue";
import Navbar from "../Shared/Layouts/Navbar.vue";
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { router } from '@inertiajs/vue3';
import FlashNotification from '@/Components/FlashNotification.vue';

// Fallback routes if `route()` helper is unavailable
const logoutRoute = typeof route === 'function' ? route('logout') : '/logout';
const loginRoute = typeof route === 'function' ? route('login') : '/login';

const timeoutMinutes = 15; // Set timeout duration here
const timeLeft = ref(timeoutMinutes * 60); // seconds countdown
const showTimeoutModal = ref(false);

let countdownInterval = null;

// Reset the timer on user activity
function resetTimer() {
  if (!showTimeoutModal.value) {
    timeLeft.value = timeoutMinutes * 60;
  }
}

// Logout and mark session timed out
function handleLogout() {
  localStorage.setItem('session_timed_out', 'true');

  router.post(logoutRoute, {}, {
    onSuccess: () => {
      window.location.href = loginRoute;
    },
    onError: () => {
      window.location.href = loginRoute;
    },
  });
}

onMounted(() => {
  // Check if we previously timed out and user refreshed the page
  if (localStorage.getItem('session_timed_out')) {
    localStorage.removeItem('session_timed_out');

    // ✅ Delay redirect to let Vue mount fully (avoids blank screen)
    setTimeout(() => {
      window.location.href = loginRoute;
    }, 0);
    return;
  }

  // User activity listeners
  const events = ['mousemove', 'keydown', 'click', 'touchstart'];
  events.forEach(ev => window.addEventListener(ev, resetTimer));

  // Countdown logic
  countdownInterval = setInterval(() => {
    if (timeLeft.value > 0) {
      timeLeft.value--;
    } else if (!showTimeoutModal.value) {
      showTimeoutModal.value = true;
      clearInterval(countdownInterval);
    }
  }, 1000);
});

onBeforeUnmount(() => {
  clearInterval(countdownInterval);
  const events = ['mousemove', 'keydown', 'click', 'touchstart'];
  events.forEach(ev => window.removeEventListener(ev, resetTimer));
});

// Format seconds into MM:SS
function formatTime(seconds) {
  const m = Math.floor(seconds / 60).toString().padStart(2, '0');
  const s = (seconds % 60).toString().padStart(2, '0');
  return `${m}:${s}`;
}
</script>

<template>
  <div class="sb-nav-fixed">
    <Navbar />
    <div id="layoutSidenav">
      <Sidenav />

      <div id="layoutSidenav_content">
        <main class="mb-5">
          <slot />
        </main>
      </div>

        <div>
        <FlashNotification />
        </div>

      <!-- Live session countdown -->
      <div style="position: fixed; bottom: 10px; right: 10px; background: #eee; padding: 8px 12px; border-radius: 4px;">
        Session Timeout in: {{ formatTime(timeLeft) }}
      </div>

      <!-- Timeout modal -->
      <div v-if="showTimeoutModal" class="modal-overlay">
        <transition name="fade-scale">
          <div class="modal-content">
            <h2>Session Expired</h2>
            <p>Your session has timed out due to inactivity.</p>
            <button @click="handleLogout">Go to Login</button>
          </div>
        </transition>
      </div>
    </div>
  </div>
</template>

<style scoped>
#layoutSidenav_content {
  background: #f5f5f5;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.65);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 10000;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 10px;
  text-align: center;
  max-width: 400px;
  width: 90%;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
}

.modal-content h2 {
  margin-bottom: 1rem;
}

.modal-content button {
  background-color: #1d4ed8;
  color: white;
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1rem;
  margin-top: 1rem;
}

/* Animate only modal-content */
.fade-scale-enter-active,
.fade-scale-leave-active {
  transition: all 0.4s ease;
}
.fade-scale-enter-from,
.fade-scale-leave-to {
  opacity: 0;
  transform: scale(0.9);
}
.fade-scale-enter-to,
.fade-scale-leave-from {
  opacity: 1;
  transform: scale(1);
}
</style>
