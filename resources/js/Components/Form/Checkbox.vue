<script setup>
import { computed } from "vue";

const props = defineProps({
    label: {
        type: String,
        default: "label"
    },
    propertyValue: {
        type: String,
        default: "value"
    },
    options: Array,
    modelValue: [Array],
    errors: {
        type: Object,
        default: {}
    },
    name: String
});

const proxyModelValue = computed({
    get() {
        return props.modelValue;
    },

    set(value) {
        emit("update:modelValue", value);
    }
});
const emit = defineEmits(["update:modelValue"]);
</script>
<template>
    <div v-bind="$attrs">
        <div v-for="option in options"
             class="flex">
            <input :id="option[propertyValue]"
                   :value="option[propertyValue]"
                   v-model="proxyModelValue"
                   class="h-4 w-4
                          border border-gray-300
                          rounded-sm
                          bg-white
                          checked:bg-skin-primary-0 checked:border-skin-primary-0
                          focus:outline-none
                          transition duration-200
                          mt-1
                          align-top
                          bg-no-repeat
                          bg-center
                          bg-contain
                          float-left
                          mr-2
                          cursor-pointer"
                   type="checkbox">
            <label :for="option[propertyValue]"
                   class="form-check-label inline-block text-skin-base dark:text-skin-base-dark">
                {{ option[label] }}
            </label>
        </div>
    </div>
    <div v-if="errors[name]"
         class="text-red-300 text-sm mt-4">* {{ errors[name] }}
    </div>
</template>
<style scoped></style>
