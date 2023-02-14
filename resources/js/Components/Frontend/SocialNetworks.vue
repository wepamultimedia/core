<script setup>
import storeFrontend from "@core/Store/frontend";
import { computed, onBeforeMount, ref } from "vue";

const props = defineProps(["iconClass"])

const site = computed(() => {
    return storeFrontend.getters["site"];
});

const socialVetworks = ref([
    "facebook",
    "twitter",
    "youtube",
    "skype",
    "linkedin",
    "instagram",
    "vimeo",
    "twitch",
    "whatsapp"
]);

onBeforeMount(() => {
    storeFrontend.dispatch("loadSite");
});

</script>
<template>
    <template v-for="social in socialVetworks">
        <a v-if="site[social] !== null"
           :title="social"
           class="flex items-center"
           :href="social === 'whatsapp' ? `https://wa.me/${site[social]}`: site[social]"
           target="_blank">
            <button v-bind="$attrs"
                    type="button">
                <slot name="icon">
                    <inline-svg :src="`/vendor/core/icons/social-networks/${social}.svg`"
                                class="w-5 h-5"/>
                </slot>
            </button>
        </a>
    </template>
</template>
<style scoped></style>
