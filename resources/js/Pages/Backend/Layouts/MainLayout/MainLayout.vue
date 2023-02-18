<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import Sidebar from "@core/Pages/Backend/Layouts/MainLayout/Partials/Sidebar.vue";
import Dropdown from "@/Core/Components/Dropdown.vue";
import FlashAlertQueue from "@/Core/Components/FlashAlert/FlashAlertQueue.vue";
import DarkModeToggle from "@/Core/Components/DarkModeToggle.vue";
import { computed, onMounted, ref } from "vue";
import { breakpointsTailwind, useBreakpoints } from "@vueuse/core";
import { useStore } from "vuex";

const props = defineProps({
    title: String,
    icon: String,
    bc: {
        type: Array,
        default: []
    }
});

const store = useStore();
const breakpoints = useBreakpoints(breakpointsTailwind);
const smallerMd = breakpoints.smaller("md");
const showDesktop = ref(true);
const showMobile = ref(false);
const loading = computed(() => store.getters["loading"]);

onMounted(() => {
    let timeoutLoading = null;

    Inertia.on("start", () => {
        //timeoutLoading = setTimeout(() => store.dispatch("loading", true), 400);
    });

    Inertia.on("finish", () => {
        // clearTimeout(timeoutLoading);
        // store.dispatch("loading", false);
    });

});

const show = ref(true);

const logout = () => {
    Inertia.post(route("logout"));
};
</script>
<template>
    <Head :title="`${__(title)} | ${$page.props.default.appName}`"/>
    <FlashAlertQueue/>
    <div class="flex flex-col justify-between min-h-screen admin-theme bg-skin-background dark:bg-skin-background-dark">
        <!--Header-->
        <div class="h-max flex md:items-center px-4 py-4 flex-col md:flex-row">
            <div class="flex items-center justify-between md:min-w-[256px]">
                <!--Logo-->
                <slot name="desktop-logo">
                    <div class="h-10 w-full bg-contain bg-left-bottom bg-no-repeat logo"></div>
                </slot>
                <!--Hamburguer icon desktop-->
                <button :class="{'-rotate-90' : !showDesktop}"
                        class="h-6 w-6 ease-in-out duration-500 hidden md:block"
                        @click="showDesktop = !showDesktop">
                    <icon class="w-6 stroke-skin-base dark:stroke-skin-base-dark fill-skin-base dark:fill-skin-base-dark"
                          icon="menu"
                          outline/>
                </button>
                <!--Hamburguer icon mobile-->
                <button v-if="smallerMd"
                        v-show="!showMobile"
                        class="h-6 w-6 ease-in-out duration-500 md:hidden"
                        @click="showMobile = !showMobile">
                    <icon class="w-6 stroke-skin-base dark:stroke-skin-base-dark fill-skin-base dark:fill-skin-base-dark"
                          icon="menu"
                          outline/>
                </button>
            </div>
            <div class="flex-auto mt-4 md:mt-0">
                <div class="flex pl-4 justify-end items-center">
                    <div class="flex">
                        <DarkModeToggle class="mr-4"></DarkModeToggle>
                        <Dropdown class="inline-block"
                                  right
                                  shadow>
                            <template #button="{open}">
                                <button v-if="$page.props.jetstream.managesProfilePhotos"
                                        class="flex items-center text-skin-base dark:text-skin-base-dark text-sm rounded-full transition pr-2">
                                    <img :alt="$page.props.user.name"
                                         :src="$page.props.user.profile_photo_url"
                                         class="h-8 w-8 rounded-full object-cover mr-2">
                                    <span class="hidden md:block">{{ $page.props.user.name }}</span>
                                    <inline-svg :class="{'rotate-180': open}"
                                                class="transition-all duration-200 ease-out fill-skin-dark dark:fill-skin-light grow ml-2"
                                                src="/vendor/core/icons/solid/chevron-down.svg"/>
                                </button>
                            </template>
                            <div class="flex flex-col min-w-[200px] divide-y divide-gray-200 dark:divide-gray-700 text-skin-base dark:text-skin-base-dark">
                                <button class="px-4 py-4 text-left">
                                    <a :href="route('admin.user.profile')">
                                        {{ __("profile") }}
                                    </a>
                                </button>
                                <!-- Authentication -->
                                <Link :href="route('logout')"
                                      as="button"
                                      class="px-4 py-4 text-left"
                                      method="post">
                                    {{ __("logout") }}
                                </Link>
                            </div>
                        </Dropdown>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-auto">
            <!-- Sidebar -->
            <Sidebar v-model:show-desktop="showDesktop"
                     v-model:show-mobile="showMobile"
                     class="md:ml-4 bg-skin-primary"
                     dark/>
            <!-- Content -->
            <div v-show="!loading"
                 class="flex-auto mx-4">
                <!-- Breadcrumb -->
                <div class="text-md bg-skin-inverted dark:bg-skin-inverted-dark shadow text-skin-base dark:text-skin-base-dark rounded-lg py-2 px-4 mb-4">
                    <Link :href="route('admin.dashboard')">{{ __("dashboard") }}</Link>
                    <template v-if="bc.length">
                        <span class="px-1">/</span>
                        <template v-for="(item, index) in bc">
                            <Link v-if="item.route"
                                  :href="route(item.route)">
                                <span :class="{'font-bold': index === Object.keys(bc).length-1}">{{ __(item.label) }}
                                </span>
                            </Link>
                            <span v-else>
                                <span :class="{'font-bold': index === Object.keys(bc).length-1}">{{ __(item.label) }}
                                </span>
                            </span>
                            <span v-if="index < Object.keys(bc).length-1"
                                  class="px-1">/
                            </span>
                        </template>
                    </template>
                </div>
                <slot></slot>
            </div>
            <div v-show="loading"
                 class="flex-auto flex items-center justify-center gap-2">
                <svg aria-hidden="true"
                     class="animate-spin stroke-skin-primary w-8 h-8"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.5"
                     viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                          stroke-linecap="round"
                          stroke-linejoin="round"></path>
                </svg>
                <h3 class="text-gray-400 dark:text-gray-700">{{ __("loading") }}</h3>
            </div>
        </div>
    </div>
</template>
<style scoped>
.logo-white {
    background-image : url("/vendor/core/images/logo-white.svg");
    @apply bg-contain bg-left-bottom bg-no-repeat h-10
}

.dark .logo {
    background-image : url("/vendor/core/images/logo-white.svg");
    @apply bg-contain bg-left-bottom bg-no-repeat h-10
}

.logo {
    background-image : url("/vendor/core/images/logo.svg");
    @apply bg-contain bg-left-bottom bg-no-repeat h-10
}
</style>
