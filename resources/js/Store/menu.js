import { defineStore } from "pinia";
import { ref } from "vue";

export const useMenuStore = defineStore("menu", () => {
    const topmenuActive = ref(0);

    function reset() {
        count.value = 0;
    }

    function setMenu(menuId) {
        topmenuActive.value = menuId;
    }

    return { topmenuActive, reset, setMenu };
});
