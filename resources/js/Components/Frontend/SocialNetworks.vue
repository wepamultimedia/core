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
    <nav>
        <ul class="[&>li]:inline-block [&>li]:mx-1">
            <template v-for="social in socialNetworks">
                <li v-if="site[social] !== null">
                    <a :href="social === 'whatsapp' ? `https://wa.me/${site[social]}`: site[social]"
                       :title="social"
                       class="flex items-center justify-center"
                       target="_blank">
                        <slot name="icon">
                            <inline-svg :src="`/vendor/core/icons/social-networks/${social}.svg`"
                                        class="w-5 h-5"/>
                        </slot>
                    </a>
                </li>
            </template>
        </ul>
    </nav>
</template>
<style scoped></style>
