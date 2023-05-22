<script setup>
import { useDark, useToggle } from "@vueuse/core";

const props = defineProps({
    label: Boolean,
    iconClass: {
        type: String,
        default: "fill-skin-base"
    },
    labelClass: {
        type: String,
        default: "text-skin-base"
    }
});

const isDark = useDark();
const toggleDark = useToggle(isDark);
</script>
<template>
    <button :aria-label="isDark ? __('change_mode_light') : __('change_mode_dark')"
            @click="toggleDark()">
        <span v-if="!isDark"
              class="text-sm flex items-center gap-1">
            <svg :class="[iconClass]"
                 aria-hidden="true"
                 class="w-5 h-5 inline-block"
                 fill="none"
                 stroke-width="1.5"
                 viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"
                      stroke-linecap="round"
                      stroke-linejoin="round"></path>
            </svg>
            <span :class="labelClass" v-if="label">
                {{ __("dark") }}
            </span>
        </span>
        <span v-else
              class="text-sm flex items-center gap-1">
            <svg :class="[iconClass]"
                 aria-hidden="true"
                 class="w-5 h-5 inline-block"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.5"
                 viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                      stroke-linecap="round"
                      stroke-linejoin="round"></path>
            </svg>
            <span :class="labelClass" v-if="label">
                {{ __("light") }}
            </span>
        </span>
    </button>
</template>
<style scoped></style>
