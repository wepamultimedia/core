<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
    links: [Array, Object],
    callback: Function
});

const getPage = (url) => {
    const re = /.*page=([0-9]+)/;
    return url.match(re)[1];
};
</script>
<template>
    <nav v-if="links?.length > 3">
        <ul class="flex flex-wrap -mb-1">
            <li v-for="(link, key) in links"
                :key="key">
                <button v-if="link.url === null"
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border border-gray-200 dark:border-gray-500 rounded"
                        type="button"
                        v-html="link.label"/>
                <button v-else-if="callback"
                        :class="{ 'bg-skin-primary text-white': link.active }"
                        class="mr-1 mb-1 px-4 py-3 leading-4 border border-gray-200 dark:border-gray-500 rounded focus:border-skin-primary-500 focus:dark:border-gray-600"
                        type="button"
                        @click="link.active ? null: callback(getPage(link.url));"
                        v-html="link.label"/>
                <Link v-else
                      :class="{ 'bg-skin-primary text-white': link.active }"
                      :href="link.url"
                      :preserve-scroll="true"
                      :preserve-state="true"
                      class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border border-gray-200 dark:border-gray-500 rounded focus:border-skin-primary-500 focus:dark:border-gray-600"
                      type="button"
                      v-html="link.label"/>
            </li>
        </ul>
    </nav>
</template>
<style scoped></style>
