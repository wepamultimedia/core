<script setup>
import { computed, onMounted, ref, toRefs, useAttrs, watch } from "vue";
import Dropdown from "@core/Components/Dropdown.vue";
import { usePage } from "@inertiajs/inertia-vue3";

const props = defineProps({
    modelValue: [String, Object],
    legend: String,
    autoresize: {
        type: Boolean,
        default: true
    },
    value: String,
    required: Boolean,
    errors: {
        type: Object,
        default: {}
    },
    label: String,
    locale: String,
    translation: Boolean,
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

const attrs = useAttrs();
const selectedLocale = ref(usePage().props.value.default.locale);
const inputId = ref(null);
const inputValue = ref();
const error = ref();
const textarea = ref();
const emit = defineEmits(["update:modelValue", "update:locale", "update:value"]);
const {
          translation,
          errors,
          locale,
          value,
          autoresize
      } = toRefs(props);


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
const resize = () => {
    if (autoresize.value) {
        textarea.value.style.height = "auto";
        if (inputValue.value) {
            textarea.value.style.height = `${textarea.value.scrollHeight + 2}px`;
        }
    }
};

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

onMounted(() => {
    if (autoresize.value) resize();
    watch(value, value => {
        resize();
        inputValue.value = value;
    });
    watch(inputValue, value => {
        emit("update:value", value);
        setInputValue(value);
        resize();
    });
});

buildInputValue();
</script>
<template>
    <template v-if="!inputId">id or name attribute is not defined</template>
    <template v-else>
        <div>
            <label :for="inputId"
                   class="block font-medium text-sm">
                <span>{{ label }}</span>
                <span v-if="required"
                      class="px-1">*
                </span>
            </label>
            <div class="flex flex-col mt-1">
                <div class="w-full">
                    <textarea :id="inputId"
                              ref="textarea"
                              v-model="inputValue"
                              :class="{'resize-none overflow-hidden' : autoresize}"
                              :rows="autoresize ? 1 : $attrs.rows"
                              class="px-3 py-2
                                 transition-transform
                                 transform
                                 ease-in-out
                                 placeholder-gray-300 dark:placeholder-gray-500
                                 bg-white dark:bg-inherit
                                 border border-gray-300 dark:border-gray-700
                                 dark:text-light
                                 focus:border-gray-300 focus:dark:border-gray-700
                                 focus:ring
                                 focus:outline-none
                                 focus:ring-gray-300 focus:dark:ring-gray-600
                                 focus:ring-opacity-50
                                 rounded-md
                                 shadow-sm
                                 block
                                 w-full"
                              v-bind="$attrs"/>
                    <div v-if="legend"
                         class="text-sm mt-1 text-gray-400 dark:text-gray-400">
                        {{ legend }}
                    </div>
                </div>
                <div v-if="error"
                     class="text-red-300 text-sm mt-1">* {{ error }}
                </div>
                <Dropdown v-if="translation && $page.props.default.locales.length > 1"
                          center
                          class="w-full">
                    <template #button="{open}">
                        <button class="py-2.5 w-full px-4 my-2 bg-white dark:bg-gray-500 border rounded-lg border-gray-300 dark:border-gray-700 uppercase text-sm"
                                type="button">
                            {{ selectedLocale }}
                        </button>
                    </template>
                    <div class="grid divide-y divide-y-gray-300 dark:divide-gray-700 bg-white dark:bg-gray-800 rounded overflow-hidden">
                        <button v-for="loc in $page.props.default.locales"
                                v-show="loc.code !== selectedLocale"
                                class="px-4 py-2 text-sm hover:dark:bg-gray-900"
                                type="button"
                                @click="selectedLocale=loc.code">
                            {{ loc.name }}
                        </button>
                    </div>
                </Dropdown>
            </div>
        </div>
    </template>
    <pre v-if="debug">{{ proxyModelValue }}</pre>
</template>