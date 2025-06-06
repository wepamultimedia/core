<script setup>
import Flap from "@core/Components/Flap.vue";
import {usePage} from "@inertiajs/vue3";
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

const error = computed(() => {
    for (const [errorKey, errorValue] of Object.entries(props.errors)) {
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
        let imageUrl = usePage().props.default.fileManagerUrl + "/" + image.file
        fileManager.open = false;
        fileManager.selectedImage = image;
        emits("update:image", image);
        emits("change", image);
        emits("update:modelValue", image.file);
        emits("update:file_name", image.file);
        emits("update:url", imageUrl);
        emits("update:title", image.name);
        emits("update:alt_name", image.alt_name);
        emits("update:description", image.description);
    }
});

const proxyUrl = computed(() => {
    let propsDefault = usePage().props.default;
    let baseUrl = propsDefault.fileManagerUrl + "/";

    if (fileManager.selectedImage.hasOwnProperty("file")) {
        return baseUrl + fileManager.selectedImage.file;
    }

    if(typeof props.modelValue === "string" && props.modelValue.startsWith('http')){
        return props.modelValue;
    } else if (props.modelValue){
        return baseUrl + props.modelValue
    }
    return '';
});
</script>
<template>
    <div>
        <label v-if="label"
               class="text-sm font-bold mr-2">
            {{ label }}
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
