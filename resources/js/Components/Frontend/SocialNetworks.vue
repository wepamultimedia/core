<script setup>
import { computed, onBeforeMount, ref } from "vue";
import { useStore } from "vuex";

const props = defineProps({
    ulClass: {
        type: String,
        default: "flex items-center [&>li]:mx-2 first:[&>li]:ml-0 last:[&>li]:mr-0"
    },
    iconClass: {
        type: String,
        default: "w-5 h-5"
    }
});

const store = useStore();

const site = computed(() => {
    return store.getters["frontend/site"];
});

const socialNetworks = ref([
    {name: "facebook", url: "https://facebook.com"},
    {name: "twitter", url: "https://twitter.com"},
    {name: "youtube", url: "https://youtube.com"},
    {name: "skype", url: ""},
    {name: "linkedin", url: "https://www.linkedin.com/in"},
    {name: "instagram", url: "https://instagram.com"},
    {name: "vimeo", url: "https://vimeo.com"},
    {name: "twitch", url: "https://twitch.com"},
    {name: "whatsapp", url: "https://wa.me"}
]);

onBeforeMount(() => {
    store.dispatch("frontend/loadSite");
});
</script>
<template>
    <nav>
        <ul :class="[ulClass]">
            <template v-for="social in socialNetworks">
                <li v-if="site[social.name] !== null">
                    <a :href="`${social.url}/${site[social.name]}`"
                       :title="social"
                       class="flex items-center justify-center"
                       target="_blank">
                        <slot name="icon">
                            <inline-svg :src="`/vendor/core/icons/social-networks/${social.name}.svg`"
                                        :class="[iconClass]"/>
                        </slot>
                    </a>
                </li>
            </template>
        </ul>
    </nav>
</template>
<style scoped></style>
