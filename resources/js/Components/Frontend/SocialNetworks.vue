<script setup>
import { computed, onBeforeMount, ref } from "vue";
import { useStore } from "vuex";

const props = defineProps(["iconClass"]);

const store = useStore();

const site = computed(() => {
    return store.getters["frontend/site"];
});

const socialNetworks = ref([
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
    store.dispatch("frontend/loadSite");
});
</script>
<template>
    <div class="flex items-center">
        <template v-for="social in socialNetworks">
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
