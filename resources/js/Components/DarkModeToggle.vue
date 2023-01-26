<script setup>
import { useDark, useToggle } from "@vueuse/core";

const isDark = useDark();
const toggleDark = useToggle(isDark);
</script>
<template>
    <button @click="toggleDark()">
        <inline-svg src="/vendor/core/icons/outline/moon.svg"
                    class="stroke-skin-base dark:stroke-skin-base-dark"
                    v-if="!isDark"/>
        <inline-svg src="/vendor/core/icons/outline/sun.svg"
                    class="stroke-skin-base dark:stroke-skin-base-dark"
                    v-else/>
    </button>
</template>
<style scoped></style>