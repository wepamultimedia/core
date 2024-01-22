<script setup>
import {computed, reactive, ref, toRefs, useAttrs, watch} from "vue";
import Dropdown from "@/Vendor/Core/Components/Dropdown.vue";
import {component as CKEditor} from "@ckeditor/ckeditor5-vue";
import Editor from "wepa-ckeditor5-filemanager";
import Flap from "@/Vendor/Core/Components/Flap.vue";
import FileManager from "@/Vendor/Core/Components/Backend/FileManager.vue";
import {usePage} from "@inertiajs/vue3";
import {useStore} from "vuex";
import _ from "lodash";

const props = defineProps({
    modelValue: [String, Object],
    required: Boolean,
    config: Object,
    maxHeight: {
        type: Number,
        default: 800
    },
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


const defaultLocale = usePage().props.default.locale;
switch (defaultLocale) {
    case "es":
        import("wepa-ckeditor5-filemanager/build/translations/es.js");
        break;
}
const ckconfig = {
    ...props.config,
    language: defaultLocale,
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
const inputId = computed(() => {
    if (attrs.id) {
        return attrs.id;
    } else if (attrs.name) {
        return attrs.name + "-" + Math.floor(Math.random() * 1000);
    }
});

const store = useStore();
const attrs = useAttrs();
const selectedLocale = computed(() => store.getters["backend/formLocale"]);
const emit = defineEmits(["update:modelValue", "update:value"]);
const {translation, errors, locale} = toRefs(props);

const proxyModelValue = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit("update:modelValue", value);
    }
});

const proxyValue = computed({
    get() {
        if (typeof proxyModelValue.value === "object" && proxyModelValue.value) {
            if (props.translation) {
                if (proxyModelValue.value.hasOwnProperty('translations')
                    && proxyModelValue.value.translations.hasOwnProperty(selectedLocale.value)
                    && proxyModelValue.value.translations[selectedLocale.value][attrs["name"]]) {
                    return proxyModelValue.value.translations[selectedLocale.value][attrs["name"]];
                }

                return ""

            } else if (proxyModelValue.value.hasOwnProperty(attrs["name"])) {
                return proxyModelValue.value[attrs["name"]];
            }
            return "";
        }

        return proxyModelValue.value
    },
    set(value) {
        if (typeof proxyModelValue.value === "object" && proxyModelValue.value) {
            if (props.translation) {
                if (value) {
                    buildTranslations();
                    proxyModelValue.value.translations[selectedLocale.value][attrs["name"]] = value;

                } else if (proxyModelValue.value.translations.hasOwnProperty(selectedLocale.value)) {
                    proxyModelValue.value.translations[selectedLocale.value][attrs["name"]] = value;
                    removeEmptyTranslations();
                }
            } else {
                proxyModelValue.value[attrs["name"]] = value;
            }
        } else {
            proxyModelValue.value = value;
        }

        emit("update:value", value);
    }
});

const error = computed(() => {
    let found = false;
    for (const [errorKey, errorValue] of Object.entries(errors.value)) {
        const re = new RegExp("[.]" + attrs.name + "$", 'g');
        const rex = new RegExp("^" + attrs.name + "$", 'g');
        if (re.test(errorKey) || rex.test(errorKey)) {
            found = true;
            if (typeof errorValue === "object") {
                return errorValue[0];
            }
            return errorValue;
        }
    }
    if (!found) {
        return null;
    }
});

function buildTranslations() {
    if (typeof proxyModelValue.value === "object" && proxyModelValue.value && props.translation) {
        if (!proxyModelValue.value.hasOwnProperty("translations")) {
            proxyModelValue.value["translations"] = {};
        }

        if (!proxyModelValue.value.translations.hasOwnProperty(selectedLocale.value)) {
            proxyModelValue.value.translations[selectedLocale.value] = {};
            proxyModelValue.value.translations[selectedLocale.value][attrs["name"]] = "";
        }
    }
}

const removeEmptyTranslations = _.throttle(() => {
    Object.keys(proxyModelValue.value.translations).forEach(locale => {
        let any = false;
        Object.keys(proxyModelValue.value.translations[locale]).forEach(property => {
            if (proxyModelValue.value.translations[locale][property]) {
                any = true;

                return true;
            }
        });
        if (!any) {
            delete proxyModelValue.value.translations[locale];
        }
    });
}, 5000);
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
            <CKEditor :id="inputId"
                      v-model="proxyValue"
                      :config="ckconfig"
                      :editor="Editor"></CKEditor>
        </div>
        <div v-if="translation"
             class="flex justify-end mt-2">
            <Dropdown v-if="$page.props.default.locales.length > 1"
                      center
                      class="w-full">
                <template #button="{open}">
                    <button class="w-full py-2.5 px-4 bg-white dark:bg-gray-500 border border rounded-lg border-gray-300 dark:border-gray-700 uppercase text-sm"
                            type="button">
                        {{ selectedLocale }}
                    </button>
                </template>
                <div class="grid divide-y divide-y-gray-300 dark:divide-gray-700 bg-white dark:bg-gray-800 rounded overflow-hidden">
                    <button v-for="loc in $page.props.default.locales"
                            v-show="loc.code !== selectedLocale"
                            class="px-4 py-2 text-sm hover:dark:bg-gray-900"
                            type="button"
                            @click="store.dispatch('backend/formLocale', loc.code)">
                        {{ loc.name }}
                    </button>
                </div>
            </Dropdown>
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
<style>
.ck-editor__editable {
    max-height: v-bind(maxHeight+ "px");
    @apply min-h-[260px]
}

.ck.ck-content ul {
    @apply ml-2 list-disc list-outside [&>li]:ml-4;
}

.ck.ck-content ol {
    @apply ml-2 list-decimal list-outside [&>li]:ml-4;
}
</style>
