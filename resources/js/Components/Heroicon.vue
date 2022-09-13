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
        return `/core/icons/solid/${icon.value}.svg`;
    }

    return `/core/icons/outline/${icon.value}.svg`;
});

const defaultClass = computed(() => {
    if (!outline.value) {
        return `fill-dark`;
    }

    return `stroke-dark`;
});
</script>
<template>
    <InlineSvg v-if="icon"
               :src="src"
               :class="[defaultClass]"/>
</template>
<style scoped></style>