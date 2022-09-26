<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in links"
                      :key="key">
                <div v-if="link.url === null"
                     class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border border-gray-200 dark:border-gray-500 rounded"
                     v-html="link.label"/>
                <Link v-else
                      :class="{ 'bg-skin-primary text-skin-base-dark': link.active }"
                      :href="link.url"
                      :preserve-scroll="true"
                      :preserve-state="true"
                      class="mr-1 mb-1 px-4 py-3 text-sm text-skin-base dark:text-skin-base-dark leading-4 border border-gray-200 dark:border-gray-500 rounded focus:border-skin-primary-500 focus:dark:border-gray-600"
                      v-html="link.label"/>
            </template>
        </div>
    </div>
</template>
<script setup>
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    links: Array
});
</script>
<style scoped></style>