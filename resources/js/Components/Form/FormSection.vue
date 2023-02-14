<script setup>
import { computed, useSlots } from "vue";

defineEmits(["submitted"]);
const hasActions = computed(() => !!useSlots().actions);
</script>
<template>
    <form @submit.prevent="$emit('submitted')">
        <div class="p-6">
            <slot name="form"></slot>
        </div>
        <div class="flex items-center p-3 text-skin-base dark:text-skint-base-dark justify-end bg-gray-200 dark:bg-gray-500 ">
            <slot name="actions">
                <button class="btn btn-success"
                        type="submit">
                    {{ __("save") }}
                </button>
            </slot>
        </div>
    </form>
</template>
<style scoped></style>
