<script setup>
import { reactive, ref, toRefs, useAttrs, watch } from "vue";
import Flap from "@/Core/Components/Flap.vue";
import FileManager from "@/Core/Components/Backend/FileManager.vue";

const props = defineProps({
    showImage: {
        type: Boolean,
        default: true
    },
    image: String,
    showInfo: {
        type: Boolean,
        default: false
    },
    reduce: Boolean,
    modelValue: String,
    name: String,
    alt: String,
    title: String,
    description: String,
    label: String,
    errors: Object
});

const emits = defineEmits(["update:modelValue", "update:title", "update:alt", "update:description"]);


const attrs = useAttrs();
const error = ref();
const {
          errors,
          modelValue
      } = toRefs(props);

watch(errors, value => {
    for (const [errorKey, errorValue] of Object.entries(value)) {
        const re = new RegExp(attrs.name + "$");
        if (re.test(errorKey)) {
            error.value = errorValue;
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
        emits("update:modelValue", image.url);
        emits("update:title", image.name);
        emits("update:alt", image.alt_name);
        emits("update:description", image.description);
    }
});
</script>
<template>
    <label v-if="label"
           class="text-sm">{{ label }}
    </label>
    <figure v-if="modelValue && showImage"
            class="mb-4 mt-1">
        <img :alt="alt"
             :src="modelValue"
             class="rounded-lg">
    </figure>
    <button class="btn btn-default w-full justify-center"
            v-bind="$attrs"
            @click.prevent="fileManager.open = true">{{ __("select_image") }}
    </button>
    <div v-if="error"
         class="text-red-300 text-sm mt-1">* {{ error }}
    </div>
    <Flap v-model="fileManager.open"
          close-background
          xl>
        <FileManager @change="fileManager.insert"></FileManager>
    </Flap>
</template>
<style scoped></style>
