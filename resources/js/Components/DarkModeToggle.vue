<script setup>
import { useDark, useToggle } from "@vueuse/core";

const isDark = useDark();
const toggleDark = useToggle(isDark);
</script>
<template>
    <button @click="toggleDark()">
        <inline-svg src="/vendor/core/icons/outline/moon.svg"
                    class="stroke-skin-base  w-5 h-5"
                    v-if="!isDark"/>
        <inline-svg src="/vendor/core/icons/outline/sun.svg"
                    class="stroke-skin-base  w-5 h-5"
                    v-else/>
    </button>
</template>
<style scoped></style>
