<script setup>
import { reactive, ref, toRefs, useAttrs, watch } from "vue";
import Flap from "@/Vendor/Core/Components/Flap.vue";
import FileManager from "@/Vendor/Core/Components/Backend/FileManager.vue";

const props = defineProps({
    showImage: {
        type: Boolean,
        default: true
    },
    buttonLabel: String,
    image: Object,
    showInfo: {
        type: Boolean,
        default: false
    },
    reduce: Boolean,
    modelValue: String,
    alt: String,
    title: String,
    description: String,
    label: String,
    errors: Object
});

const emits = defineEmits(["update:modelValue", "update:image", "update:title", "update:alt", "update:description"]);


const attrs = useAttrs();
const error = ref();
const {
          errors,
          modelValue
      } = toRefs(props);

watch(errors, value => {
    for (const [errorKey, errorValue] of Object.entries(value)) {
        const re = new RegExp("[.]" + attrs.name + "$");
        const rex = new RegExp("^" + attrs.name + "$");
        if (re.test(errorKey) || rex.test(errorKey)) {
            if (typeof errorValue === "object") {
                error.value = errorValue[0];
            } else {
                error.value = errorValue;
            }
            return;
        } else {
            error.value = null;
        }
    }
});

const fileManager = reactive({
    open: false,
    selectedImage: null,
    insert: image => {
        fileManager.open = false;
        fileManager.selectedImage = image;
        emits("update:image", image);
        emits("update:modelValue", image.url);
        emits("update:title", image.name);
        emits("update:alt", image.alt_name);
        emits("update:description", image.description);
    }
});
</script>
<template>
    <div class="w-full">
        <label v-if="label"
               class="text-sm">{{ label }}
        </label>
        <figure v-if="modelValue && showImage"
                class="mb-4 mt-1">
            <img :alt="alt"
                 :src="modelValue"
                 class="rounded-lg">
        </figure>
        <button class="btn btn-secondary w-full justify-center mt-1"
                v-bind="$attrs"
                @click.prevent="fileManager.open = true">
            {{ buttonLabel ? buttonLabel : __("select_image") }}
        </button>
        <div v-if="error"
             class="text-red-300 text-sm mt-1">* {{ error }}
        </div>
        <Flap v-model="fileManager.open"
              close-background
              xl>
            <FileManager @change="fileManager.insert"></FileManager>
        </Flap>
    </div>
</template>
<style scoped></style>
