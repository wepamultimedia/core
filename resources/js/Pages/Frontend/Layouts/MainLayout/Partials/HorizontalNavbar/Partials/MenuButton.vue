<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    label: String,
    href: String
});
</script>
<template>
    <Link as="button"
          class="uppercase
                 min-w-max
                 px-4
                 py-2
                 h-full
                 before:block
                 before:bg-skin-primary
                 before:absolute
                 before:h-1
                 before:w-0
                 before:right-0
                 before:bottom-0
                 hover:text-skin-primary
                 hover:before:w-full
                 hover:before:left-0
                 before:transition-all
                 before:duration-500
                 before:ease-in-out
                 relative"
          type="button">
        <slot></slot>
    </Link>
</template>
<style scoped></style>
