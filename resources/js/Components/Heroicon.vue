<script setup>
import InlineSvg from "vue-inline-svg";
import { computed, toRefs } from "vue";

const props = defineProps({
    icon: String,
    outline: Boolean,
    size: {
        type: String,
        default: "4"
    }
});

const {
          outline,
          icon,
          size
      } = toRefs(props);

const src = computed(() => {
    if (!outline.value) {
        return `/vendor/core/icons/solid/${icon.value}.svg`;
    }

    return `/vendor/core/icons/outline/${icon.value}.svg`;
});

const defaultClass = computed(() => {
    if (!outline.value) {
        return `fill-skin-base`;
    }

    return `stroke-skin-base`;
});
</script>
<template>
    <InlineSvg v-if="icon"
               :src="src"
               :class="[defaultClass]"/>
</template>
<style scoped></style>