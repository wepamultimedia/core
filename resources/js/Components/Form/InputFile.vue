<script setup>
import { reactive, ref, toRefs, useAttrs, watch } from "vue";
import Flap from "@/Vendor/Core/Components/Flap.vue";
import FileManager from "@/Vendor/Core/Components/Backend/FileManager.vue";

const props = defineProps({
    buttonLabel: String,
    file: Object,
    buttonClass: {
        type: String,
        default: "btn btn-secondary"
    },
    modelValue: String,
    label: String,
    errors: Object,
    extensions: {
        type: Array,
        default: ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'pdf', 'zip', 'rar']
    }
});

const emits = defineEmits(["update:modelValue", "update:file", "change"]);

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
    insert: file => {
        fileManager.open = false;
        fileManager.selectedImage = file;
        emits("update:file", file);
        emits("change", file);
        emits("update:modelValue", file.url);
        emits("update:name", file.name);
    }
});
</script>
<template>
    <div>
        <label v-if="label"
               class="text-sm">{{ label }}
        </label>
        <button :class="[buttonClass, {'mt-1': label}]"
                @click.prevent="fileManager.open = true">
            {{ buttonLabel ? buttonLabel : __("select_file") }}
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
