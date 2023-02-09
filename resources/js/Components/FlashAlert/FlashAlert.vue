<script setup>
import { toRefs, watch } from "vue";
import Alert from "@core/Components/FlashAlert/Partials/Alert.vue"

const props = defineProps({
    modelValue: Boolean,
    message: String,
    type: {
        type: String,
        required: true,
        default: "success",
        validator(value) {
            return ["info", "success", "warning", "error"].includes(value);
        }
    },
    timeout: {
        type: Number,
        default: 10000
    }
});

const {modelValue, timeout} = toRefs(props);
const emit = defineEmits(["update:modelValue"]);

let showTimeout = null;

watch(modelValue, value => {
    startTimeout();
});

function startTimeout() {
    showTimeout = setTimeout(() => emit("update:modelValue", false), timeout.value);
}

function mouseHover() {
    clearTimeout(showTimeout);
}

function mouseLeave() {
    startTimeout();
}

function close() {
    emit("update:modelValue", false);
    clearTimeout(showTimeout);
}

if (modelValue.value) {
    startTimeout();
}
</script>
<template>
    <div class="grid gap-2 z-30 w-[90%] md:w-1/2 xl:w-1/4 fixed left-1/2 -translate-x-1/2 bottom-5">
        <transition name="alert">
            <Alert v-if="modelValue" @close="close()" :class="[
                     {'bg-red-500': type === 'error'},
                     {'bg-blue-500': type === 'info'},
                     {'bg-orange-500': type === 'warning'},
                     {'bg-green-500': type === 'success'},
                 ]"
                   @mouseleave="mouseLeave()"
                   @mouseover="mouseHover()">
                <slot></slot>
            </Alert>
        </transition>
    </div>
</template>
<style scoped>
.alert-enter-from, .alert-leave-to {
    @apply opacity-0
}

.alert-enter-to, .alert-leave-from {
    @apply opacity-100
}

.alert-enter-active, .alert-leave-active {
    @apply transition-all ease-in-out duration-500
}
</style>
