<script setup>
import { reactive, ref, toRefs, useAttrs, watch } from "vue";
import Dropdown from "@core/Components/Dropdown.vue";
import { component as CKEditor } from "@ckeditor/ckeditor5-vue";
import Editor from "wepa-ckeditor5-filemanager";
import Flap from "@/Core/Components/Flap.vue";
import FileManager from "@/Core/Components/Backend/FileManager.vue";
import { usePage } from "@inertiajs/inertia-vue3";

const props = defineProps({
    modelValue: [String, Object],
    required: Boolean,
    config: Object,
    locale: String,
    errors: {
        type: Object,
        default: {}
    },
    label: String,
    translation: Boolean,
    fileManagerCallBack: {
        type: Function,
        default: () => {
        }
    },
    debug: Boolean
});

const ckconfig = {
    ...props.config,
    filemanager: editor => {
        fileManager.instance = editor;
        fileManager.open = true;
    }
};
const inputId = ref(null);
const fileManager = reactive({
    instance: null,
    open: false,
    selectedImage: null,
    insert: image => {
        fileManager.open = false;
        fileManager.instance.execute("insertImage", {
            source: {
                src: image.url,
                title: image.name,
                alt: image.alt_name
            }
        });
    }
});
const attrs = useAttrs();
const pageProps = usePage().props.value;
const selectedLocale = ref(pageProps.locale);
const inputValue = ref();
const error = ref();
const emit = defineEmits(["update:modelValue", "update:locale"]);
const {
          translation,
          errors,
          modelValue,
          locale
      } = toRefs(props);

watch(locale, value => {
    if(selectedLocale.value !== value) {
        selectedLocale.value = value;
    }
});
watch(selectedLocale, value => {
    if(selectedLocale.value !== locale.value) {
        emit("update:locale", value);
    }
    buildInputValue();
});
watch(inputValue, value => setInputValue(value));
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

const buildInputValue = () => {
    if (attrs.id) {
        inputId.value = attrs.id;
    } else if (attrs.name) {
        inputId.value = attrs.name + "-" + Math.floor(Math.random() * 1000);
    }
    if (typeof modelValue.value === "object" && modelValue.value !== null) {
        if (translation.value) {
            if (!modelValue.value["translations"]) {
                modelValue.value["translations"] = {};
            }

            modelValue.value["translations"] = Object.assign({}, modelValue.value["translations"]);

            if (modelValue.value["translations"][selectedLocale.value]) {
                inputValue.value = modelValue.value["translations"][selectedLocale.value][attrs.name]
                                   ? modelValue.value["translations"][selectedLocale.value][attrs.name] : "";
            } else {
                inputValue.value = "";
            }
        } else {
            inputValue.value = modelValue.value[attrs["name"]];
        }
    } else {
        inputValue.value = modelValue.value;
    }
};
const setInputValue = (value) => {
    if (typeof modelValue.value === "object" && modelValue.value) {
        if (translation.value) {
            if (value) {
                if (!modelValue.value["translations"].hasOwnProperty(selectedLocale.value)) {
                    modelValue.value["translations"][selectedLocale.value] = {};
                }
                modelValue.value["translations"][selectedLocale.value][attrs["name"]] = value;
            } else if(modelValue.value.translations.hasOwnProperty(selectedLocale.value)){
                if (modelValue.value["translations"][selectedLocale.value].hasOwnProperty(attrs["name"])) {
                    delete modelValue.value["translations"][selectedLocale.value][attrs["name"]];
                    Object.keys(modelValue.value.translations).forEach(locale => {
                        if (!Object.keys(modelValue.value.translations[locale]).length) {
                            delete modelValue.value.translations[locale];
                        }
                    });
                }
            }
        } else {
            modelValue.value[attrs["name"]] = value;
        }

        emit("update:modelValue", modelValue);
    } else {
        emit("update:modelValue", value);
    }
};

buildInputValue();
</script>
<template>
    <div>
        <label :for="inputId"
               class="block font-medium text-sm">
            <span>{{ label }}</span>
            <span v-if="required"
                  class="px-1">*
            </span>
        </label>
        <div class="mt-1 text-gray-800"
             style="--ck-border-radius: 0.40rem">
            <CKEditor v-model="inputValue"
                      :id="inputId"
                      :config="ckconfig"
                      :editor="Editor"></CKEditor>
            <div class="flex justify-end mt-2">
                <Dropdown v-if="$page.props.locales.length > 1"
                          class="w-full">
                    <template #button="{open}">
                        <button class=" w-full py-2.5 px-4 bg-white dark:bg-gray-500 border border rounded-lg border-gray-300 dark:border-gray-700 uppercase text-sm"
                                type="button">
                            {{ selectedLocale }}
                        </button>
                    </template>
                    <div class="grid divide-y divide-y-gray-300">
                        <button v-for="locale in $page.props.locales"
                                class="px-4 py-2 text-sm"
                                type="button"
                                @click="selectedLocale=locale.code">
                            {{ locale.name }}
                        </button>
                    </div>
                </Dropdown>
            </div>
        </div>
        <div v-if="error"
             class="text-red-300 text-sm mt-1">* {{ error }}
        </div>
        <pre v-if="debug">{{ modelValue }}</pre>
        <Flap v-model="fileManager.open"
              close-background
              xl>
            <FileManager @change="fileManager.insert"></FileManager>
        </Flap>
    </div>
</template>
<style scoped>
.ck-editor__editable {
    @apply min-h-[260px]
}
</style>
