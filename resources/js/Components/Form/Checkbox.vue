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
    modelValue: [Array, Boolean, Number],
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
    <div>
        <div v-for="option in options"
             v-if="options"
             class="flex items-start">
            <input :id="option[propertyValue]"
                   v-model="proxyModelValue"
                   :value="option[propertyValue]"
                   class="checkbox"
                   type="checkbox">
            <label :for="option[propertyValue]"
                   class="form-check-label text-sm inline-block text-skin-base ">
                {{ option[label] }}
            </label>
        </div>
        <div v-else
             class="flex items-center">
            <input :id="label" v-model="proxyModelValue"
                   class="checkbox"
                   type="checkbox">
            <label :for="label"
                   class="form-check-label inline-block text-skin-base  text-sm">
                {{ label }}
            </label>
        </div>
        <div v-if="errors[name]"
             class="text-red-300 text-sm mt-4">* {{ errors[name] }}
        </div>
    </div>
</template>
<style scoped>
.checkbox {
    @apply
    h-4 w-4
    rounded-sm
    bg-white
    focus:checked:bg-skin-primary
    hover:checked:bg-skin-primary
    checked:bg-skin-primary
    focus:ring-skin-primary
    transition duration-200
    mr-2
    cursor-pointer
}
</style>
