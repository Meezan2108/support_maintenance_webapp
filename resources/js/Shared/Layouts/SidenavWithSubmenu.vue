<script setup>
import SidenavWithSubmenu from "./SidenavWithSubmenu.vue";

import { useMenuStore } from "@/Store/menu.js";

import { usePage, Link, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const activeMenuCode = computed(() => usePage().props.activeMenuCode);

const props = defineProps({
    menu: Object,
    showIcon: {
        type: Boolean,
        default: true,
    },
});

const activeMenu = ref(false);
const menuStore = useMenuStore();
const topmenuActive = computed(() => menuStore.topmenuActive);

const appBaseUrl = usePage().props.appBaseUrl;

const defineActiveMenu = () => {
    for (let menuChildren of props.menu.children) {
        if (activeMenuCode.value == menuChildren.code) {
            menuStore.setMenu(props.menu.parent_id);
            activeMenu.value = true;
        }
    }
};

defineActiveMenu();

router.on("success", (event) => {
    activeMenu.value = false;
    defineActiveMenu();
});
</script>

<template>
    <a
        class="nav-link"
        :class="{
            active: activeMenu || topmenuActive == menu.id,
            collapsed: !activeMenu,
        }"
        href="#"
        data-bs-toggle="collapse"
        :data-bs-target="'#collapseLayouts' + menu.id"
        aria-expanded="false"
        :aria-controls="'collapseLayouts' + menu.id"
    >
        <div class="sb-nav-link-icon" v-if="showIcon">
            <span class="material-icons">
                {{ menu.icon }}
            </span>
        </div>
        {{ menu.name }}
        <div class="sb-sidenav-collapse-arrow">
            <i class="fas fa-angle-down"></i>
        </div>
    </a>
    <div
        class="collapse"
        :class="{
            show: activeMenu || topmenuActive == menu.id,
        }"
        :id="'collapseLayouts' + menu.id"
        aria-labelledby="headingOne"
        :data-bs-parent="
            menu.parent_id != 0
                ? '#sidenavAccordionPage' + menu.parent_id
                : '#sidenavAccordion'
        "
    >
        <nav
            class="sb-sidenav-menu-nested nav"
            :id="'sidenavAccordionPage' + menu.id"
        >
            <template
                v-for="childrenMenu in menu.children"
                :key="childrenMenu.id"
            >
                <Link
                    v-if="childrenMenu.type == 0"
                    class="nav-link"
                    :class="{
                        active: activeMenuCode == childrenMenu.code,
                        'ps-5': !showIcon,
                    }"
                    :href="appBaseUrl + '/' + childrenMenu.code"
                    preserve-state
                >
                    {{ childrenMenu.name }}
                </Link>

                <SidenavWithSubmenu
                    v-if="childrenMenu.type == 2"
                    :menu="childrenMenu"
                    :showIcon="false"
                />
            </template>
        </nav>
    </div>
</template>
