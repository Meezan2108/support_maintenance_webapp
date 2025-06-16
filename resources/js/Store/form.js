import { defineStore } from "pinia";
import { ref } from "vue";

export const useFormStore = defineStore("form", () => {
    const isDirty = ref(false);

    function reset() {
        isDirty.value = false;
    }

    function setIsDirty(status) {
        isDirty.value = status;
    }

    return { isDirty, reset, setIsDirty };
});
