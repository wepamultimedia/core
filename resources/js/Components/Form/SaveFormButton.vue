<script setup>
import { toRefs } from "vue";

const props = defineProps(["form", "loading"]);

const { form } = toRefs(props)
</script>
<template>
    <button class="btn btn-success flex items-center gap-2"
            :disabled="form.processing || loading"
            type="submit">
        <span v-if="form.processing || loading">
            <svg aria-hidden="true"
                 class="animate-spin w-4 h-4"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.5"
                 viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                      stroke-linecap="round"
                      stroke-linejoin="round"></path>
            </svg>
        </span>
        <span>
            <slot>
                {{ __("save") }}
            </slot>
        </span>
    </button>
</template>
<style scoped></style>
