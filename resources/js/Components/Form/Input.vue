<script setup>
import { computed, onMounted, ref, toRefs, useAttrs, watch } from "vue";
import Dropdown from "@core/Components/Dropdown.vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    modelValue: [String, Object, Number],
    legend: String,
    value: String,
    required: Boolean,
    locale: String,
    errors: {
        type: Object,
        default: {}
    },
    label: String,
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

const input = ref(null);
const attrs = useAttrs();
const inputId = ref(null);
const selectedLocale = ref(usePage().props.default.locale);
const inputValue = ref("");
const error = ref();
const emit = defineEmits(["update:modelValue", "update:locale", "update:value"]);
const {
          translation,
          errors,
          locale,
          value
      } = toRefs(props);

watch(locale, value => {
    if (selectedLocale.value !== value) {
        selectedLocale.value = value;
    }
});
watch(selectedLocale, value => {
    emit("update:locale", value);
    buildInputValue();
});
watch(inputValue, value => {
    emit("update:value", value);
    setInputValue(value);
});
watch(value, value => {
    inputValue.value = value;
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
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});

const buildInputValue = () => {
    if (attrs.id) {
        inputId.value = attrs.id;
    } else if (attrs.name) {
        inputId.value = attrs.name + "-" + Math.floor(Math.random() * 1000);
    }

    if (typeof proxyModelValue.value === "object" && proxyModelValue.value !== null) {
        if (translation.value) {
            if (!proxyModelValue.value.hasOwnProperty("translations")) {
                proxyModelValue.value["translations"] = {};
            }

            proxyModelValue.value["translations"] = Object.assign({}, proxyModelValue.value["translations"]);

            if (proxyModelValue.value.translations.hasOwnProperty(selectedLocale.value)) {
                inputValue.value = proxyModelValue.value.translations[selectedLocale.value].hasOwnProperty(attrs.name)
                                   ? proxyModelValue.value.translations[selectedLocale.value][attrs.name] : value.value
                                                                                                            ? value.value
                                                                                                            : "";
            } else {
                inputValue.value = "";
            }
        } else {
            inputValue.value = proxyModelValue.value[attrs["name"]];
        }
    } else {
        inputValue.value = proxyModelValue.value;
    }
    emit("update:value", inputValue.value);
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
                                any = true;
                                return true;
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
        proxyModelValue.value = value
    }
};

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
            <div class="flex items-center mt-1">
                <button v-if="$slots.icon"
                        class="py-2.5 px-2 bg-white dark:bg-gray-500 border border-r-0 rounded-l-lg border-gray-300 dark:border-gray-700 uppercase text-sm"
                        type="button">
                    <slot name="icon"></slot>
                </button>
                <div class="w-full">
                    <input :id="inputId"
                           ref="input"
                           v-model="inputValue"
                           :class="[
                               {'rounded-r-none': translation && $page.props.default.locales.length > 1},
                               {'rounded-l-none': $slots.icon}
                               ]"
                           class="px-3 py-2
                                  placeholder-gray-300 dark:placeholder-gray-500
                                  bg-white dark:bg-inherit
                                  outline-transparent
                                  border border-gray-300 dark:border-gray-700
                                  dark:text-light
                                  focus:border-gray-300 focus:dark:border-gray-700
                                  focus:ring
                                  focus:outline-none
                                  focus:ring-gray-300 focus:dark:ring-gray-700
                                  focus:ring-opacity-50
                                  disabled:opacity-60
                                  rounded-md
                                  shadow-sm
                                  block
                                  w-full"
                           type="text"
                           v-bind="$attrs">
                    <div v-if="legend"
                         class="text-sm mt-1 text-gray-400 dark:text-gray-400">
                        {{ legend }}
                    </div>
                </div>
                <Dropdown v-if="translation && $page.props.default.locales.length > 1"
                          right>
                    <template #button="{open}">
                        <button class="py-2.5 px-4 bg-white dark:bg-gray-500 border-t border-b border-r rounded-r-lg border-gray-300 dark:border-gray-700 uppercase text-sm"
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
            <div v-if="error"
                 class="text-red-300 text-sm mt-1">* {{ error }}
            </div>
        </div>
    </template>
    <pre v-if="debug">{{ proxyModelValue }}</pre>
</template>
