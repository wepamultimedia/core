<script setup>
import { computed, onMounted, ref, toRefs, watch } from "vue";
import SidebarItems from "@pages/Vendor/Core/Backend/Layouts/MainLayout/Partials/SidebarItems.vue";
import { breakpointsTailwind, useBreakpoints } from "@vueuse/core";
import { useStore } from "vuex";

const props = defineProps({
    showDesktop: {
        type: Boolean,
        default: false
    },
    showMobile: {
        type: Boolean,
        default: false
    },
    dark: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(["update:showDesktop", "update:showMobile"]);

const store = useStore();
const {showDesktop, showMobile} = toRefs(props);
const breakpoints = useBreakpoints(breakpointsTailwind);
const maximized = ref(false);

const menu = computed(() => store.getters['menu']);
const smallerMd = breakpoints.smaller("md");
const minimized = computed(() => {
    if (smallerMd.value) {
        return false;
    } else if (!showDesktop.value && maximized.value) {
        return false;
    } else if (!showDesktop.value) {
        return true;
    } else {
        return !showDesktop.value;
    }
});

const showDesktopToggle = () => {
    emit("update:showDesktop", !showDesktop.value)
}

onMounted((app) => {
    store.dispatch('menu', 'backend');
    if (window.localStorage.showdesktop) {
        emit("update:showDesktop", localStorage.showdesktop === "true");
    }
    watch(showDesktop, async (value) => {
        window.localStorage.showdesktop = value;
    });
});
</script>
<template>
    <div v-if="showMobile"
         class="bg-black inset-0 absolute opacity-25"
         @click="emit('update:showMobile', false)"></div>
    <!--Background items-->
    <div :class="[
            {'md:max-w-[56px] min-w-[56px]':minimized},
            {'md:min-w-[256px] md:max-w-[256px]':!minimized},
            {'translate-x-0 -left-0' : showMobile},
            {'-translate-x-full -left-full': !showMobile},
         ]"
         v-bind="$attrs"
         class="fixed
                z-20
                left-0
                right-10
                top-0
                bottom-0
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
         @mouseleave="maximized = false"
         @mouseover="maximized = true">
        <div class="flex items-center px-4 py-4 md:hidden md:opacity-0 justify-between">
            <slot name="mobile-logo">
                <div class="h-10 w-full bg-contain bg-left-bottom bg-no-repeat logo-white"></div>
            </slot>
            <!--Hamburguer icon mobile-->
            <button :class="{'-rotate-90' : showMobile}"
                    class="h-6 w-6 ease-in-out duration-500"
                    @click="emit('update:showMobile', !showMobile)">
                <icon :class="[{'stroke-skin-light': showMobile}]"
                      class="stroke-skin-base w-6 h-6"
                      icon="menu"
                      outline></icon>
            </button>
        </div>
        <div :class="{'mx-2.5' : minimized}"
             class="overflow-hidden m-4 ease-in-out duration-500">
            <!--Menu items-->
            <template v-for="item in menu">
                <div :class="{dark:dark}">
                    <SidebarItems :item="item" :key="item.id" button-class="text-white" @click="emit('update:showMobile', false)"
                                  :minimized="minimized"/>
                </div>
            </template>
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
