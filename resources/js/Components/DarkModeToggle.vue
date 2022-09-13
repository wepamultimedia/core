<template>
    <button class="text-dark dark:text-light pr-2"
            @click="toggleDark()">
        <inline-svg src="/core/icons/outline/moon.svg"
                    class="stroke-dark dark:stroke-light"
                    v-if="!isDark"/>
        <inline-svg src="/core/icons/outline/sun.svg"
                    class="stroke-dark dark:stroke-light"
                    v-else/>
    </button>
</template>
<script setup>
import { useDark, useToggle } from "@vueuse/core";

const isDark = useDark();
const toggleDark = useToggle(isDark);
</script>
<style scoped></style>