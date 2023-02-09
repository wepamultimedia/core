<script setup>
const props = defineProps({
    modelValue: Boolean,
    buttonClass: String,
    lineClass: {
        type: String,
        default: 'bg-gray-800'
    }
});

const emit = defineEmits(["update:modelValue"]);
</script>
<template>
    <nav class="relative md:hidden">
        <button :class="[buttonClass]"
                class="w-12 h-12 relative focus:outline-none rounded"
                @click="emit('update:modelValue', !modelValue)">
            <span class="block w-8 absolute left-1/2 top-1/2   transform  -translate-x-1/2 -translate-y-1/2">
                <span :class="[lineClass, {'rotate-45': modelValue,' -translate-y-[8px]': !modelValue }]"
                      class="lines"></span>
                <span :class="[lineClass, {'opacity-0': modelValue }]"
                      class="lines"></span>
                <span :class="[lineClass, {'-rotate-45': modelValue, ' translate-y-[8px]': !modelValue}]"
                      class="lines"></span>
            </span>
        </button>
    </nav>
</template>
<style scoped>
.lines{
    @apply block absolute h-[3px] w-full transform transition duration-500 ease-in-out rounded;
}
</style>
