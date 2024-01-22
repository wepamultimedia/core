<script setup>
import {computed, onMounted, ref, toRefs, useAttrs, watch} from "vue";
import Dropdown from "@/Vendor/Core/Components/Dropdown.vue";
import _ from "lodash";
import {useStore} from "vuex";

const props = defineProps({
    modelValue: [String, Object, Number],
    legend: String,
    value: String,
    required: Boolean,
    errors: {
        type: Object,
        default: {}
    },
    label: String,
    translation: Boolean,
    debug: Boolean,
    limit: Array,
    limitLabel: Boolean
});

const store = useStore();
const input = ref(null);
const attrs = useAttrs();
const inputId = computed(() => {
    if (attrs.id) {
        return attrs.id;
    } else if (attrs.name) {
        return attrs.name + "-" + Math.floor(Math.random() * 1000);
    }
});
const selectedLocale = computed(() => store.getters["backend/formLocale"]);
const emit = defineEmits(["update:modelValue", "update:value"]);
const {translation, errors, locale, value} = toRefs(props);

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

                    emit("update:value", proxyModelValue.value.translations[selectedLocale.value][attrs["name"]]);

                    return proxyModelValue.value.translations[selectedLocale.value][attrs["name"]];
                }

                return null

            } else if (proxyModelValue.value.hasOwnProperty(attrs["name"])) {

                emit("update:value", proxyModelValue.value[attrs["name"]]);

                return proxyModelValue.value[attrs["name"]];
            }
            return null;
        }

        emit("update:value", proxyModelValue.value);
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


const progressbar = computed(() => {
    if (proxyValue.value?.length > 0) {
        const percent = (proxyValue.value.length / props.limit[1]) * 100;
        return Math.round(percent) > 100 ? 100 : Math.round(percent);
    }
    return 0;
});


onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }

    watch(value, value => {
        proxyValue.value = value;
    });
});

defineExpose({focus: () => input.value.focus()});
</script>
<template>
    <template v-if="!inputId">id or name attribute is not defined</template>
    <template v-else>
        <div>
            <label :for="inputId"
                   class="block font-bold text-sm mb-0.5">
                <span>{{ label }}</span>
                <span v-if="required"
                      class="px-1">*
                </span>
            </label>
            <div class="flex items-center">
                <slot name="left">
                    <div v-if="$slots.icon"
                         class="py-2.5 px-2 bg-white dark:bg-gray-500 border border-r-0 rounded-l-lg border-gray-300 dark:border-gray-700 uppercase text-sm"
                         type="button">
                        <slot name="icon"></slot>
                    </div>
                </slot>
                <div class="w-full">
                    <input :id="inputId"
                           ref="input"
                           v-model="proxyValue"
                           :class="[
                               {'!rounded-r-none': translation && $page.props.default.locales.length > 1},
                               {'!rounded-l-none': $slots.icon}
                           ]"
                           class="input"
                           type="text"
                           v-bind="$attrs">
                    <div v-if="limit"
                         class="w-full mt-2">
                        <div :class="{'bg-green-700': proxyValue?.length >= limit[0] && proxyValue?.length <= limit[1], 'bg-orange-600': proxyValue?.length < limit[0], 'bg-red-600' : proxyValue?.length > limit[1]}"
                             :style="`width: ${progressbar}%`"
                             class="h-1"></div>
                        <span v-if="limitLabel"
                              class="text-xs">{{ progressbar }}% | {{ proxyValue?.length }} / {{ limit[1] }}
                        </span>
                    </div>
                    <div v-if="legend"
                         class="text-sm mt-1 text-gray-400 dark:text-gray-400">
                        {{ legend }}
                    </div>
                </div>
                <div class="self-start">
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
                                    @click="store.dispatch('backend/formLocale', loc.code)">
                                {{ loc.name }}
                            </button>
                        </div>
                    </Dropdown>
                </div>
            </div>
            <div v-if="error"
                 class="text-red-300 text-sm mt-1">* {{ error }}
            </div>
        </div>
    </template>
    <pre v-if="debug">{{ proxyModelValue }}</pre>
</template>
