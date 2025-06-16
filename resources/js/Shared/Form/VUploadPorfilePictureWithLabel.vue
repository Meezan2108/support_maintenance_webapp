<script setup>
import { watch, ref } from "vue";
import VueMultiselect from "vue-multiselect";

const props = defineProps({
    elId: String,
    label: String,
    old_picture: {
        type: String,
        default: "",
    },
    value: Object,
    error: String,
});

const picture = ref(null);
const isHasFile = ref(props.initPicture ? true : false);
const elPicturePreview = ref(null);

const emits = defineEmits(["update:value", "update:old_picture"]);

watch(picture, (newValue) => {
    if (newValue) {
        var src = URL.createObjectURL(newValue);
        elPicturePreview.value.src = src;
        isHasFile.value = true;
    } else {
        elPicturePreview.value.src = "";
        isHasFile.value = false;
    }

    emits("update:value", newValue);
});

const removeOldPicture = function () {
    emits("update:old_picture", "");
};
</script>

<template>
    <div class="d-flex align-items-center">
        <label
            :for="elId"
            class="select-picture label-pointer d-flex align-items-center justify-content-center"
            :class="{ 'border-error': error }"
        >
            <img
                ref="elPicturePreview"
                class="preview-picture"
                :src="old_picture"
                :class="{ 'd-none': !isHasFile && !old_picture }"
            />
            <span
                class="material-icons"
                :class="{ 'd-none': isHasFile || old_picture }"
            >
                photo_camera
            </span>
        </label>
        <div class="ms-3">
            <label :for="elId" class="label-pointer fw-bold">
                Upload Picture
                <br />
                <span class="fw-normal font-small text-secondary">
                    (500px x 500px)
                </span>
            </label>
            <br />
            <button
                class="btn btn-sm btn-light text-danger"
                v-if="old_picture"
                @click="removeOldPicture"
            >
                Remove
            </button>
            <input
                type="file"
                :id="elId"
                class="d-none"
                @input="picture = $event.target.files[0]"
            />
        </div>
    </div>
    <div v-if="error" class="row">
        <div class="col-sm-9 offset-sm-3 text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
