<script setup>
import { computed, ref, toRef, useAttrs } from "vue";

const props = defineProps({
    modelValue: [Boolean, Number],
    property: String,
    name: String,
    lg: Boolean,
    xs: Boolean,
    label: [
        String,
        Array
    ],
    icon: [
        String,
        Array
    ]
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
const setId = () => {
    if (attrs.id) {
        return attrs.id;
    } else if (attrs.name) {
        return attrs.name + '-' + Math.random() * 1000;
    }

    return 'toggle-' + Math.random();
};

const id = setId();
const modelValue = toRef(props, "modelValue");
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
                   :checked="proxyModelValue"
                   :id="id"
                   :name="name"
                   @change="toggle"
                   class="sr-only">
            <!-- line -->
            <div class="relative flex items-center w-[40px] h-[1.5rem]  rounded-full transition"
                 :class="{'w-[60px] h-[2rem]': lg, 'w-[30px] h-[1rem]': xs, 'bg-green-600 dark:bg-green-600': proxyModelValue, 'bg-gray-400 dark:bg-gray-500': !proxyModelValue}">
                <!-- dot -->
                <div class="bg-white translate-x-1 w-4 h-4 rounded-full transition duration-500"
                     :class="[
                         {'translate-x-[20px]': proxyModelValue && !lg && !xs, 'bg-skin-primary-0 ': proxyModelValue},
                         { 'w-[0.6rem] h-[0.6rem]': xs, 'translate-x-[16px]': proxyModelValue && xs, 'w-[1.5rem] h-[1.5rem]': lg, 'translate-x-[32px]': proxyModelValue && lg}]"></div>
            </div>
        </div>
        <!-- label On -->
        <div v-if="labelOn"
             class="ml-3 font-medium">
            <span v-if="proxyModelValue || !labelOff"> {{ labelOn }}</span>
            <span v-else> {{ labelOff }}</span>
        </div>
    </label>
</template>
<style scoped></style>
