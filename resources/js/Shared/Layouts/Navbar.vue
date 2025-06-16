<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import PopNotification from "./Notifications/PopNotification.vue";
import { useNotificationStore } from "../../Store/notification";

const notifStore = useNotificationStore();
const user = computed(() => usePage().props.authUser);
const countNotif = computed(() => notifStore.count ?? 0);
const appBaseUrl = usePage().props.appBaseUrl;

// Real-time date & time
const currentTime = ref("");

function updateTime() {
    const now = new Date();
    const options = { month: 'short', day: '2-digit', year: 'numeric' };
    const date = now.toLocaleDateString(undefined, options);
    const time = now.toLocaleTimeString(); // with seconds
    currentTime.value = `${date} — ${time}`;
}

let intervalId;
onMounted(() => {
    updateTime();
    intervalId = setInterval(updateTime, 1000);
});

onBeforeUnmount(() => {
    clearInterval(intervalId);
});
</script>

<template>
    <nav class="sb-topnav navbar navbar-expand bg-white shadow-sm">
        <!-- Sidebar Toggle-->
        <button
            class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 ms-lg-3 ms-0"
            id="sidebarToggle"
            href="#!"
        >
            <i class="fas fa-bars"></i>
        </button>

        <!-- Logos for small screens (default left alignment) -->
        <a class="navbar-brand ps-3 d-flex align-items-center d-lg-none " href="#">
            <img
                :src="appBaseUrl + '/assets/images/st-adv-logo.png'"
                class="brand-1 me-2"
            />
            <img
                :src="appBaseUrl + '/assets/images/datablu-logo.png'"
                class="brand-1"
            />
        </a>

        <!-- Centered logos for desktop only -->
        <div class="d-none d-lg-flex mx-auto align-items-center">
            <img
                :src="appBaseUrl + '/assets/images/st-adv-logo.png'"
                class="brand-1 me-2"
            />
            <img
                :src="appBaseUrl + '/assets/images/datablu-logo.png'"
                class="brand-1"
            />
        </div>

        <!-- Navbar Icons -->
        <ul class="navbar-nav ms-auto ms-md-0 me-lg-4 align-items-center">
            <li class="nav-item dropdown">
                <PopNotification />
            </li>

            <!-- DateTime Display -->
            <li class="nav-item d-none d-lg-block">
                <span class="nav-link clock-text">
                    {{ currentTime }}
                </span>
            </li>

            <li class="nav-item dropdown">
                <a
                    class="nav-link d-flex align-items-center justify-content-center"
                    id="navbarDropdown"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <span v-if="user.picture" class="profile-picture">
                        <img :src="user.picture" class="preview-picture" />
                    </span>
                    <i v-else class="fas fa-user fa-fw"></i>
                    <span class="ms-2 d-none d-lg-block">{{ user.name }}</span>
                </a>
                <ul
                    class="dropdown-menu dropdown-menu-end"
                    aria-labelledby="navbarDropdown"
                >
                    <li>
                        <Link class="dropdown-item" href="/profile">
                            Profile
                        </Link>
                    </li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <Link
                            class="dropdown-item"
                            href="/logout"
                            method="post"
                            as="button"
                        >
                            Logout
                        </Link>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</template>

<style scoped>
.new-notif {
    left: 70%;
    top: 5px;
    width: 25px;
    height: 25px;
    font-size: 9px;
}

.brand-1 {
    height: 30px;
}

.mx-auto {
    gap: 0.5rem;
}

.clock-text {
    font-size: 0.9rem;
    font-weight: 500;
    color: #6c757d; /* muted */
    white-space: nowrap;
    padding-right: 1rem;
}

@media (max-width: 1500px) {
    .navbar-brand img {
        height: 30px;
    }
}
</style>
