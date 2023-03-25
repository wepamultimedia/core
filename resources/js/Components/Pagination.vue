<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
    links: [Array, Object],
    callback: Function
});

const active = ref("1");

const getPage = (url) => {
    const re = /.*page=([0-9]+)/;
    return url.match(re)[1];
};
</script>
<template>
    <nav v-if="links">
        <ul class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in links"
                      :key="key">
                <li>
                    <button v-if="link.url === null"
                            class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border border-gray-200 dark:border-gray-500 rounded"
                            type="button"
                            v-html="link.label"/>
                    <button v-else-if="callback"
                            :class="{ 'bg-skin-primary text-white': link.active }"
                            class="mr-1 mb-1 px-4 py-3 leading-4 border border-gray-200 dark:border-gray-500 rounded focus:border-skin-primary-500 focus:dark:border-gray-600"
                            type="button"
                            @click="active !== getPage(link.url) ? callback(getPage(link.url)) : null; active = getPage(link.url)"
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
            </template>
        </ul>
    </nav>
</template>
<style scoped></style>
