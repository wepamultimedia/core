<script setup>
import { rand } from "@vueuse/core";
import { ref, defineEmits, defineProps, toRef } from "vue";

const props = defineProps({
    modelValue: Boolean,
    id: {
        type: String,
        default: `toggle-${rand(2000, 15000)}`
    },
    name: String,
    lg: Boolean,
    label: [
        String,
        Array
    ],
    icon: [
        String,
        Array
    ]
});

const label = toRef(props, "label");
const labelOn = ref("");
const labelOff = ref("");

if (Array.isArray(label.value)) {
    if (label.value.length > 1) {
        labelOn.value = label.value[0];
        labelOff.value = label.value[1];
    } else {
        labelOff.value = label.value[0];
        labelOn.value = label.value[0];
    }
} else {
    labelOn.value = label.value;
}

const icon = toRef(props, "label");
const iconOn = ref("");
const iconOff = ref("");

if (Array.isArray(icon.value)) {
    if (icon.value.length > 1) {
        iconOn.value = icon.value[0];
        iconOff.value = icon.value[1];
    } else {
        iconOff.value = icon.value[0];
        iconOn.value = icon.value[0];
    }
} else {
    iconOn.value = icon.value;
}

const emit = defineEmits(["update:modelValue"]);

const toggle = (event) => {
    emit("update:modelValue", event.target.checked);
};
</script>
<template>
    <label :for="id"
           class="flex items-center cursor-pointer text-light dark:text-dark">
        <!-- toggle -->
        <div class="relative">
            <!-- input -->
            <input type="checkbox"
                   :checked="modelValue"
                   :id="id"
                   :name="name"
                   @change="toggle"
                   class="sr-only">
            <!-- line -->
            <div class="relative flex items-center w-[40px] h-[1.5rem] bg-gray-600 rounded-full transition"
                 :class="{'w-[60px] h-[2rem]': lg}">
                <!-- dot -->
                <div class="bg-white translate-x-1 w-4 h-4 rounded-full transition duration-1000"
                     :class="[
                         {'translate-x-[20px]': modelValue && !lg, 'bg-skin-primary-0 ': modelValue},
                         {'w-[1.5rem] h-[1.5rem]': lg, 'translate-x-[32px]': modelValue && lg}]"></div>
            </div>
        </div>
        <!-- label On -->
        <div v-if="labelOn"
             class="ml-3 font-medium">
            <span v-if="modelValue"> {{ labelOn }}</span>
            <span v-else> {{ labelOff }}</span>
        </div>
    </label>
</template>
<style scoped></style>
