<script setup>
import { computed, defineAsyncComponent, onMounted, reactive, ref, toRefs, useAttrs, watch } from "vue";
import Dropdown from "@/Vendor/Core/Components/Dropdown.vue";
import { component as CKEditor } from "@ckeditor/ckeditor5-vue";
import Editor from "wepa-ckeditor5-filemanager";
import Flap from "@/Vendor/Core/Components/Flap.vue";
import FileManager from "@/Vendor/Core/Components/Backend/FileManager.vue";
import { usePage } from "@inertiajs/vue3";


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

const proxyModelValue = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit("update:modelValue", value);
    }
});

const defaultLocale = usePage().props.default.locale;

switch (defaultLocale){
    case "es":
        import("wepa-ckeditor5-filemanager/build/translations/es.js");
        break;
}

const ckconfig = {
    ...props.config,
    language: 'es',
    "fontSize": {
        options: [
            {
                title: "xs",
                model: "text-xs",
                view: {
                    name: 'span',
                    classes: ['text-xs']
                }
            },
            {
                title: "small",
                model: "text-sm",
                view: {
                    name: 'span',
                    classes: ['text-sm']
                }
            },
            {
                title: "default",
                model: "text-md",
                view: {
                    name: 'span',
                    classes: ['text-xs']
                }
            },
            {
                title: "Large",
                model: "text-lg",
                view: {
                    name: 'span',
                    classes: ['text-lg']
                }
            },
            {
                title: "Extra large",
                model: "text-xl",
                view: {
                    name: 'span',
                    classes: ['text-xl']
                }
            },
            {
                title: "2 Extra large",
                model: "text-2xl",
                view: {
                    name: 'span',
                    classes: ['text-2xl']
                }
            },
            {
                title: "3 Extra large",
                model: "text-3xl",
                view: {
                    name: 'span',
                    classes: ['text-3xl']
                }
            },
            {
                title: "4 Extra large",
                model: "text-4xl",
                view: {
                    name: 'span',
                    classes: ['text-4xl']
                }
            },
        ],
    },
    toolbar: {
        items: [
            "undo",
            "redo",
            "|",
            "heading",
            "|",
            "fontSize",
            "bold",
            "italic",
            "link",
            "bulletedList",
            "numberedList",
            "|",
            "outdent",
            "indent",
            "|",
            "filemanager",
            "blockQuote",
            "insertTable",
            "mediaEmbed",

            "sourceEditing"
        ]
    },
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
const selectedLocale = ref(usePage().props.default.locale);
const inputValue = ref();
const error = ref();
const emit = defineEmits(["update:modelValue", "update:locale"]);
const {
          translation,
          errors,
          locale
      } = toRefs(props);

watch(locale, value => {
    if (selectedLocale.value !== value) {
        selectedLocale.value = value;
    }
});
watch(selectedLocale, value => {
    if (selectedLocale.value !== locale.value) {
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
    if (typeof proxyModelValue.value === "object" && proxyModelValue.value !== null) {
        if (translation.value) {
            if (!proxyModelValue.value["translations"]) {
                proxyModelValue.value["translations"] = {};
            }

            proxyModelValue.value["translations"] = Object.assign({}, proxyModelValue.value["translations"]);

            if (proxyModelValue.value["translations"][selectedLocale.value]) {
                inputValue.value = proxyModelValue.value["translations"][selectedLocale.value][attrs.name]
                                   ? proxyModelValue.value["translations"][selectedLocale.value][attrs.name] : "";
            } else {
                inputValue.value = "";
            }
        } else {
            inputValue.value = proxyModelValue.value[attrs["name"]];
        }
    } else {
        inputValue.value = proxyModelValue.value;
    }
};
const setInputValue = (value) => {
    if (typeof proxyModelValue.value === "object" && proxyModelValue.value) {
        if (translation.value) {
            if (value) {
                if (!proxyModelValue.value["translations"].hasOwnProperty(selectedLocale.value)) {
                    proxyModelValue.value["translations"][selectedLocale.value] = {};
                }
                proxyModelValue.value["translations"][selectedLocale.value][attrs["name"]] = value;
            } else if (proxyModelValue.value.translations.hasOwnProperty(selectedLocale.value)) {
                if (proxyModelValue.value["translations"][selectedLocale.value].hasOwnProperty(attrs["name"])) {
                    proxyModelValue.value["translations"][selectedLocale.value][attrs["name"]] = null;
                    Object.keys(proxyModelValue.value.translations).forEach(locale => {
                        let any = false;
                        Object.keys(proxyModelValue.value.translations[locale]).forEach(property => {
                            if (proxyModelValue.value.translations[locale][property]) {
                                return any = true;
                            }
                        });
                        if (!any) {
                            delete proxyModelValue.value.translations[locale];
                        }
                    });
                }
            }
        } else {
            proxyModelValue.value[attrs["name"]] = value;
        }
    } else {
        proxyModelValue.value = value;
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
        <div class="mt-1 text-gray-800 "
             style="--ck-border-radius: 0.40rem">
            <CKEditor :id="inputId"
                      v-model="inputValue"
                      :config="ckconfig"
                      :editor="Editor"></CKEditor>
            <div class="flex justify-end mt-2">
                <Dropdown v-if="$page.props.default.locales.length > 1"
                          class="w-full">
                    <template #button="{open}">
                        <button class="w-full py-2.5 px-4 bg-white dark:bg-gray-500 border border rounded-lg border-gray-300 dark:border-gray-700 uppercase text-sm"
                                type="button">
                            {{ selectedLocale }}
                        </button>
                    </template>
                    <div class="grid divide-y divide-y-gray-300">
                        <button v-for="locale in $page.props.default.locales"
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

.ck-content .text-xs {
    font-size : 0.7em;
}

.ck-content .text-sm {
    font-size : 0.85em;
}

.ck-content .text-md {
    font-size : 1.4em;
}

.ck-content .text-lg {
    font-size : 1.8em;
}

.ck-content .text-xl {
    font-size : 2.0em;
}

.ck-content .text-2xl {
    font-size : 2.2em;
}
</style>
