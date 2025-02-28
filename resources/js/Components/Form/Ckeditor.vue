<script setup>
import FileManager from "@core/Components/Backend/FileManager.vue";
import Dropdown from "@core/Components/Dropdown.vue";
import Flap from "@core/Components/Flap.vue";
import {usePage} from "@inertiajs/vue3";
import _ from "lodash";
import {computed, onMounted, reactive, toRefs, useAttrs} from "vue";
import {useStore} from "vuex";

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
    debug: Boolean,
    limit: Array,
    limitByWords: {
        type: Boolean,
        default: false
    },
    limitLabel: Boolean
});
const defaultLocale = usePage().props.default.locale;
switch (defaultLocale) {
    case "es":
        import("ckeditor5-classic-core/build/translations/es");
        break;
    case "fr":
        import("ckeditor5-classic-core/build/translations/fr");
        break;
    case "de":
        import("ckeditor5-classic-core/build/translations/de");
        break;
}

const ckconfig = {
    ...props.config,
    language: defaultLocale,
    filemanager: {
        open: () => fileManager.open = true,
        classes: 'float-right float-left float-left float-right mr-2 my-2 w-1/2'
    }
};

const fileManager = reactive({
    editor: null,
    open: false,
    selectedImage: null,
    insert: image => {
        fileManager.open = false;
        window?.editor.execute("insertImage", {
            source: {
                src: usePage().props.default.fileManagerUrl + '/' + image.file,
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

const counter = reactive({
    words: 0,
    characters: 0,
    counter: computed(() => {
        return props.limitByWords ? counter.words : counter.characters;
    })
})

const proxyValue = computed({
    get() {
        if (typeof proxyModelValue.value === "object" && proxyModelValue.value) {
            if (props.translation) {
                if (proxyModelValue.value.hasOwnProperty("translations")
                    && proxyModelValue.value.translations.hasOwnProperty(selectedLocale.value)
                    && proxyModelValue.value.translations[selectedLocale.value][attrs["name"]]) {
                    return proxyModelValue.value.translations[selectedLocale.value][attrs["name"]];
                }

                return "";

            } else if (proxyModelValue.value.hasOwnProperty(attrs["name"])) {
                return proxyModelValue.value[attrs["name"]];
            }
            return "";
        }

        return proxyModelValue.value;
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
        const re = new RegExp("[.]" + attrs.name + "$", "g");
        const rex = new RegExp("^" + attrs.name + "$", "g");
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

onMounted(() => {
    import("ckeditor5-classic-core").then(e => {
        e.default.create(document.querySelector("#editor"), ckconfig)
            .then(editor => {
                window.editor = editor;
                editor.model.document.on("change:data", () => {
                    proxyValue.value = editor.getData();
                });
                editor.setData(proxyValue.value);
            });
    });
});

const progressbar = computed(() => {
    if (counter.characters > 0) {
        const percent = (counter.counter / props.limit[1]) * 100;

        return Math.round(percent) > 100 ? 100 : Math.round(percent);
    }

    return 0;
});
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
        <div class="mt-1 text-gray-800 ckeditor"
             style="--ck-border-radius: 0.40rem">
            <div id="editor"></div>
            <div v-if="limit"
                 class="w-full mt-2">
                <div :class="{'bg-green-700': counter.counter >= limit[0] && counter.counter <= limit[1], 'bg-orange-600': counter.counter < limit[0], 'bg-red-600' : counter.counter > limit[1]}"
                     :style="`width: ${progressbar}%`"
                     class="h-1"></div>
                <span v-if="limitLabel"
                      class="text-xs">{{ progressbar }}% | {{ counter.counter }} / {{ limit[1] }} {{ limitByWords ? __('words') : __('characters') }}
                        </span>
            </div>
        </div>
        <div v-if="translation"
             class="flex justify-end mt-2">
            <Dropdown v-if="$page.props.default.locales.length > 1"
                      center
                      class="w-full">
                <template #button="{open}">
                    <button class="w-full py-2.5 px-4 bg-white dark:bg-gray-500 border rounded-lg border-gray-300 dark:border-gray-700 uppercase text-sm"
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
.ck.ck-editor__editable {
    max-height: v-bind(maxHeight+ "px");
    @apply min-h-[400px]
}

.ck.ck-content ul {
    @apply ml-2 list-disc list-outside [&>li]:ml-4;
}

.ck.ck-content ol {
    @apply ml-2 list-decimal list-outside [&>li]:ml-4;
}

.ck.ck-powered-by {
    display: none;
}
</style>
