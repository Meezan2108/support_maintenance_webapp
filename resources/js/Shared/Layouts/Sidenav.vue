<script setup>
import { computed, onMounted, ref } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import SidenavWithSubmenu from "./SidenavWithSubmenu.vue";

import { useTaskStore } from "@/Store/task.js";

const taskStore = useTaskStore();

const menus = computed(() => usePage().props.menus);
const taskCount = computed(() => taskStore.count);
const activeMenuCode = computed(() => usePage().props.activeMenuCode);

const appBaseUrl = usePage().props.appBaseUrl;

onMounted(() => {
    taskStore.checkCount();
});

setInterval(() => {
    taskStore.checkCount();
}, 30 * 1000);
</script>

<template>
    <div id="layoutSidenav_nav">
        <nav
            class="sb-sidenav accordion sb-sidenav-dark bg-light-green"
            id="sidenavAccordion"
        >
            <div class="sb-sidenav-menu pt-4">
                <div class="nav">
                    <template v-for="menu in menus" :key="menu.id">
                        <!-- MENU TYPE LINK -->
                        <Link
                            v-if="menu.type == 0"
                            class="nav-link"
                            :class="{
                                active: activeMenuCode == menu.code,
                            }"
                            :href="appBaseUrl + '/' + menu.code"
                            preserve-state
                        >
                            <div class="sb-nav-link-icon">
                                <span class="material-icons">{{
                                    menu.icon
                                }}</span>
                            </div>
                            {{ menu.name }}

                            <span
                                v-if="menu.code == 'my-task' && taskCount > 0"
                                class="task-notif badge rounded-pill bg-danger text-light"
                                >{{ taskCount }}</span
                            >
                        </Link>

                        <!-- MENU TYPE HEADER -->
                        <div
                            v-if="menu.type == 1"
                            class="sb-sidenav-menu-heading"
                        >
                            {{ menu.name }}
                        </div>

                        <!-- MENU TYPE WITH CHILDREN -->
                        <SidenavWithSubmenu
                            v-if="menu.type == 2"
                            :menu="menu"
                        />
                    </template>
                </div>
            </div>
        </nav>
    </div>
</template>

<style scoped>
.task-notif {
    margin-left: auto;
}
</style>
