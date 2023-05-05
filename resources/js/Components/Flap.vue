<script setup>
import { onMounted, onUnmounted, toRef, watch } from "vue";

const props = defineProps({
    modelValue: Boolean,
    removeBackground: Boolean,
    closeBackground: Boolean,
    containerClass: {
        type: String,
        default: "bg-skin-foreground"
    },
    onClose: {
        type: Function,
        default: () => {
        }
    },
    title: String,
    sm: Boolean,
    md: Boolean,
    lg: Boolean,
    xl: Boolean
});

const modelValue = toRef(props, "modelValue");
const closeBackground = toRef(props, "closeBackground");
const emit = defineEmits(["update:modelValue", "close"]);

const close = () => {
    props.onClose();
    emit("update:modelValue", false);
    emit("close");
};
const toggle = () => {
    if (modelValue.value) {
        close();
    }
    emit("update:modelValue", !modelValue.value);
};

const closeOnEscape = (e) => {
    if (e.key === "Escape" && modelValue.value) {
        close();
    }
};

onMounted(() => {
    watch(modelValue, value => {
        if (value === true) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = "auto";
        }
    });
    document.addEventListener("keydown", closeOnEscape);
});

onUnmounted(() => {
    document.removeEventListener("keydown", closeOnEscape);
});
</script>
<template>
    <transition name="flap-background">
        <div v-if="modelValue && !removeBackground"
             class="fixed inset-0 z-30 bg-white dark:bg-gray-700 bg-opacity-20 dark:bg-opacity-20 backdrop-blur"
             @click="closeBackground ? close() : false"></div>
    </transition>
    <transition name="flap-content">
        <div v-if="modelValue"
             :class="[containerClass, {'w-[95%] sm:w-3/5 md:w-[50%] lg:w-1/3 xl:w-1/4 2xl:w-1/6' : sm || (!sm && !md && !lg && !xl) },
                 {'w-[95%] sm:w-2/3 md:w-[50%] lg:w-2/5 xl:w-1/3 2xl:w-1/4' : md},
                 {'w-[95%] sm:w-5/6 md:w-3/4 lg:w-2/3 xl:w-3/5 2xl:w-1/2' : lg},
                 {'w-[95%] sm:w-5/6 md:w-4/5 lg:w-3/4 xl:w-2/3 2xl:w-3/5' : xl}]"
             class="fixed right-0 top-0 bottom-0 shadow-2xl p-6 z-30 transition-all ease-in-out overflow-y-auto">
            <h2 v-if="title"
                class="mb-4">{{ title }}
            </h2>
            <slot :toogle="toggle"/>
        </div>
    </transition>
</template>
<style scoped>
.flap-background-enter-from, .flap-background-leave-to {
    @apply opacity-0
}

.flap-background-enter-to, .flap-background-leave-from {
    @apply opacity-100
}

.flap-background-enter-active, .flap-background-leave-active {
    @apply transition-all ease-in-out duration-100
}

.flap-content-enter-from, .flap-content-leave-to {
    @apply translate-x-full
}

.flap-content-enter-to, .flap-content-leave-from {
    @apply translate-x-0
}

.flap-content-enter-active, .flap-content-leave-active {
    @apply transition-all ease-in-out duration-500
}
</style>
