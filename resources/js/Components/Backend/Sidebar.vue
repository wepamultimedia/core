<script setup>
import { computed, onMounted, ref, watch } from "vue";
import SidebarItems from "@core/Components/Backend/SidebarItems.vue";
import { usePage } from "@inertiajs/inertia-vue3";
import { breakpointsTailwind, useBreakpoints } from "@vueuse/core";

defineProps({
    bgClass: {
        type: String,
        default: "bg-gray-400"
    },
    textClass: String,
    iconClass: {
        type: String,
        default: "fill-skin-base"
    },
    dark: {
        type: Boolean,
        default: false
    }
});

const breakpoints = useBreakpoints(breakpointsTailwind);
const showMobile = ref(false);
const showDesktop = ref(true);
const maximize = ref(false);
const menu = usePage().props.value.menu;
const smallerMd = breakpoints.smaller("md");
const minimize = computed(() => {
    if (smallerMd.value) {
        return false;
    } else if (!showDesktop.value && maximize.value) {
        return false;
    } else if (!showDesktop.value) {
        return true;
    } else {
        return !showDesktop.value;
    }
});

onMounted((app) => {
    if (window.localStorage.showdesktop) {
        showDesktop.value = localStorage.showdesktop === "true";
    }
    watch(showDesktop, async (value) => {
        window.localStorage.showdesktop = value;
    });
});
</script>
<template>
    <div class="flex flex-col">
        <!--Header-->
        <div class="flex md:items-center px-4 py-4 flex-col md:flex-row">
            <div class="flex items-center justify-between md:min-w-[256px]">
                <!--Logo-->
                <slot name="desktop-logo">
                    <div class="h-10 w-full bg-contain bg-left-bottom bg-no-repeat logo"></div>
                </slot>
                <!--Hamburguer icon desktop-->
                <button :class="{'-rotate-90' : !showDesktop}"
                        class="h-6 w-6 ease-in-out duration-500 hidden md:block"
                        @click="showDesktop = !showDesktop">
                    <icon :class="[iconClass]"
                          class="w-6 stroke-skin-base dark:stroke-skin-base-dark"
                          icon="menu"
                          outline/>
                </button>
                <!--Hamburguer icon mobile-->
                <button v-if="smallerMd"
                        v-show="!showMobile"
                        class="h-6 w-6 ease-in-out duration-500 md:hidden"
                        @click="showMobile = !showMobile">
                    <icon :class="[iconClass]"
                          class="w-6 stroke-skin-base dark:stroke-skin-base-dark"
                          icon="menu"
                          outline/>
                </button>
            </div>
            <div class="flex-auto mt-4 md:mt-0">
                <slot name="header"></slot>
            </div>
        </div>
        <div class="flex gap-4 md:pl-4 flex-auto">
            <div v-if="showMobile"
                 class="bg-black inset-0 absolute opacity-25"
                 @click="showMobile = false"></div>
            <!--Background items-->
            <div :class="[{'md:max-w-[56px] min-w-[56px]':minimize, 'md:min-w-[256px] md:max-w-[256px]':!minimize, 'translate-x-0 -left-0' : showMobile, '-translate-x-full -left-full': !showMobile}, bgClass]"
                 class="fixed
                        z-20
                        inset-0
                        ease-in-out
                        duration-300
                        rounded-r-lg
                        md:translate-x-0
                        md:shadow-lg
                        md:-left-0
                        md:rounded-t-lg
                        md:rounded-b-none
                        md:relative
                        md:opacity-100"
                 @mouseleave="maximize = false"
                 @mouseover="maximize = true">
                <div class="flex items-center px-4 py-4 md:hidden md:opacity-0 justify-between">
                    <slot name="mobile-logo">
                        <div class="h-10 w-full bg-contain bg-left-bottom bg-no-repeat logo-white"></div>
                    </slot>
                    <!--Hamburguer icon mobile-->
                    <button :class="{'-rotate-90' : showMobile}"
                            class="h-6 w-6 ease-in-out duration-500"
                            @click="showMobile = !showMobile">
                        <icon :class="[{'stroke-skin-light': showMobile}, iconClass]"
                              class="stroke-skin-base dark:stroke-skin-base-dark w-6 h-6"
                              icon="menu"
                              outline></icon>
                    </button>
                </div>
                <div :class="{'mx-2.5' : minimize}"
                     class="overflow-hidden m-4 ease-in-out duration-500">
                    <!--Menu items-->
                    <template v-for="item in menu">
                        <div :class="{dark:dark}">
                            <SidebarItems :key="route().current()"
                                          :icon-class="iconClass"
                                          :item="item"
                                          :minimize="minimize"
                                          :on-click="() => showMobile = false"
                                          :text-class="textClass"
                                          dot-class="bg-skin-base dark:bg-skin-base-dark"/>
                        </div>
                    </template>
                </div>
            </div>
            <!--Content-->
            <div class="flex-auto">
                <slot/>
            </div>
        </div>
    </div>
</template>
<style scoped>
.logo-white {
    background-image : url("/images/logo-white.svg");
    @apply bg-contain bg-left-bottom bg-no-repeat h-10
}

.dark .logo {
    background-image : url("/images/logo-white.svg");
    @apply bg-contain bg-left-bottom bg-no-repeat h-10
}

.logo {
    background-image : url("/images/logo.svg");
    @apply bg-contain bg-left-bottom bg-no-repeat h-10
}
</style>
