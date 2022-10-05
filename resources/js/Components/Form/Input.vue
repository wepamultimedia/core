<script setup>
import { ref, toRefs, useAttrs, watch } from "vue";
import Dropdown from "@core/Components/Dropdown.vue";
import { usePage } from "@inertiajs/inertia-vue3";

const props = defineProps({
    modelValue: [String, Object],
    errors: {
        type: Object,
        default: {}
    },
    label: String,
    translation: Boolean
});

// Build language object
const buildTraslationsObject = () => {
    if (typeof modelValue.value == "object" && !modelValue.value[selectedLocale.value] && translation.value) {
        modelValue.value[selectedLocale.value] = {};

        return true;
    }

    return false;
};

const attrs = useAttrs();
const pageProps = usePage().props.value;
const selectedLocale = ref(pageProps.locale);

const {
          translation,
          modelValue
      } = toRefs(props);

const inputValue = ref("");

if (typeof modelValue.value == "object" && modelValue.value !== null) {
    if (translation.value) {
        buildTraslationsObject();
        inputValue.value = modelValue.value[selectedLocale.value][attrs.name];
    } else {
        inputValue.value = modelValue.value[attrs["name"]]
    }
} else {
    inputValue.value = modelValue.value;
}

const emit = defineEmits(["update:modelValue"]);

watch(selectedLocale, () => {
    buildTraslationsObject();
    inputValue.value = modelValue.value[selectedLocale.value][attrs.name];
});

watch(inputValue, value => {
    if (typeof modelValue.value == "object" && modelValue.value !== null) {
        if (translation.value) {
            modelValue.value[selectedLocale.value][attrs["name"]] = value;
        } else {
            modelValue.value[attrs["name"]] = value;
        }
        emit("update:modelValue", modelValue);
    } else {
        emit("update:modelValue", value);
    }
});

const id = () => {
    if (attrs.id) {
        return attrs.id;
    } else if (attrs.name) {
        return attrs.name;
    }

    return null;
};
</script>
<template>
    <template v-if="!id()">id or name attribute is not defined</template>
    <template v-else>
        <label :for="id()"
               class="block font-medium text-sm">
            <span>{{ label }}</span>
        </label>
        <div class="flex items-center mt-1">
            <input :id="id()"
                   v-model="inputValue"
                   :class="{'rounded-r-none': translation}"
                   class="px-3 py-2
                          placeholder-gray-300 dark:placeholder-gray-500
                          dark:bg-inherit
                          outline-transparent
                          border border-gray-300 dark:border-gray-400
                          dark:text-light
                          focus:border-gray-200 focus:dark:border-gray-400
                          focus:ring
                          focus:outline-none
                          focus:ring-gray-200 focus:dark:ring-gray-500
                          focus:ring-opacity-50
                          rounded-md
                          shadow-sm
                          block
                          w-full"
                   type="text"
                   v-bind="$attrs">
            <Dropdown v-if="translation">
                <template #button="{open}">
                    <button class="py-2.5 px-4 border border rounded-r-lg border-gray-300 dark:border-gray-400 uppercase text-sm"
                            type="button">
                        {{ selectedLocale }}
                    </button>
                </template>
                <div class="grid divide-y">
                    <button v-for="locale in $page.props.locales"
                            class="px-4 py-2 text-sm"
                            type="button"
                            @click="selectedLocale=locale.code">
                        {{ locale.name }}
                    </button>
                </div>
            </Dropdown>
        </div>
        <div v-if="errors[$attrs.name]"
             class="text-red-300 text-sm mt-1">* {{ errors[$attrs.name] }}
        </div>
    </template>
</template>
