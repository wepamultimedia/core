<script setup>
import Flap from "@/Vendor/Core/Components/Flap.vue";
import {computed, reactive, toRefs, useAttrs} from "vue";
import FileManager from "@core/Components/Backend/FileManager.vue";

const props = defineProps({
    showImage: {
        type: Boolean,
        default: true
    },
    buttonLabel: String,
    image: Object,
    buttonClass: {
        type: String,
        default: "btn btn-secondary"
    },
    showInfo: {
        type: Boolean,
        default: false
    },
    reduce: Boolean,
    modelValue: String,
    file_name: String,
    title: String,
    alt_name: String,
    url: {
        type: String,
        default: ""
    },
    description: String,
    label: String,
    errors: {
        type: Object,
        default: {}
    },
    extensions: {
        type: Array,
        default: ["jpg", "jpeg", "png", "webp"]
    }
});

const emits = defineEmits([
    "update:modelValue",
    "update:url",
    "update:file_name",
    "update:image",
    "update:title",
    "update:alt_name",
    "update:description",
    "change"
]);

const attrs = useAttrs();

const {errors, modelValue} = toRefs(props);

const error = computed(() => {
    for (const [errorKey, errorValue] of Object.entries(errors.value)) {
        const re = new RegExp("[.]" + attrs.name + "$");
        const rex = new RegExp("^" + attrs.name + "$");
        if (re.test(errorKey) || rex.test(errorKey)) {
            if (typeof errorValue === "object") {
                return errorValue[0];
            }

            return errorValue;
        } else {
            return null;
        }
    }
});

const fileManager = reactive({
    open: false,
    selectedImage: {},
    insert: image => {
        fileManager.open = false;
        fileManager.selectedImage = image;
        emits("update:image", image);
        emits("change", image);
        emits("update:modelValue", image.url);
        emits("update:file_name", image.file);
        emits("update:url", image.url);
        emits("update:title", image.name);
        emits("update:alt_name", image.alt_name);
        emits("update:description", image.description);
    }
});

const proxyUrl = computed(() => {
    if (fileManager.selectedImage.hasOwnProperty("url")) {
        return fileManager.selectedImage.url;
    }

    return props.modelValue;
});
</script>
<template>
    <div>
        <label v-if="label"
               class="text-sm font-bold mr-2">{{ label }}
        </label>
        <figure v-if="proxyUrl !==  '' && showImage"
                class="mb-4 mt-1">
            <img :alt="alt_name"
                 :src="proxyUrl"
                 class="rounded-lg">
        </figure>
        <button :class="[buttonClass, {'mt-1': label}]"
                @click.prevent="fileManager.open = true">
            {{ buttonLabel ? buttonLabel : __("select_image") }}
        </button>
        <div v-if="error"
             class="text-red-300 text-sm mt-1">* {{ error }}
        </div>
        <Flap v-model="fileManager.open"
              close-background
              xl>
            <FileManager :extensions="extensions"
                         @change="fileManager.insert"></FileManager>
        </Flap>
    </div>
</template>
<style scoped></style>
