<script setup>
import storeFrontend from "@/Vendor/Core/Store/frontend";
import { computed, onBeforeMount, ref } from "vue";

const props = defineProps(["iconClass"]);

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
    <div class="flex items-center">
        <template v-for="social in socialVetworks">
            <a v-if="site[social] !== null"
               :href="social === 'whatsapp' ? `https://wa.me/${site[social]}`: site[social]"
               :title="social"
               class="flex items-center"
               target="_blank">
                <button type="button"
                        v-bind="$attrs">
                    <slot name="icon">
                        <inline-svg :src="`/vendor/core/icons/social-networks/${social}.svg`"
                                    class="w-5 h-5"/>
                    </slot>
                </button>
            </a>
        </template>
    </div>
</template>
<style scoped></style>
