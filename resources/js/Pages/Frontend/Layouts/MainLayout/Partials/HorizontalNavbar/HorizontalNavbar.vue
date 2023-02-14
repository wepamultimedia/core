<script setup>
import { nextTick, onBeforeUnmount, onMounted, ref, toRefs } from "vue";
import { breakpointsTailwind, useBreakpoints } from "@vueuse/core";
import HamburguerButton from "@/Core/Components/HamburguerButton.vue";

const props = defineProps({
    logoSrc: String,
    logoAlt: String,
    modelValue: Boolean,
    navbarClass: {
        type: String,
        default: 'bg-gray-200 dark:bg-gray-700'
    },
    contentClass: String,
    height: String,
    container: Boolean,
    divideMenu: Boolean,
    fixed: Boolean,
    shadow: Boolean,
    transparent: Boolean,
    textColor: String
});

const breakpoints = useBreakpoints(breakpointsTailwind);
const smallerMd = breakpoints.smaller("md");
const emit = defineEmits(["update:modelValue"]);
const {modelValue} = toRefs(props);
const navbar = ref();
const navbarHeight = ref();
const showNavBar = ref(true);
const lastScrollTop = ref(0);
const unFixed = ref(true);
const open = ref(modelValue.value);
const showHeader = ref(true);

const toggleMobileMenu = () => {
    open.value = !open.value;
    emit("update:modelValue", open.value);
};

const closeMobileMenu = () => {
    open.value = false;
    emit("update:modelValue", open.value);
};

const onScroll = () => {
    if (open.value) {
        showNavBar.value = true;
    } else {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        unFixed.value = scrollTop < (navbarHeight.value + 20);
        if (scrollTop > (navbarHeight.value + 20)) {
            showHeader.value = false;
            showNavBar.value = scrollTop <= lastScrollTop.value;
            lastScrollTop.value = scrollTop;
        } else {
            showHeader.value = true;
            showNavBar.value = true;
        }
    }
};

const onResize = () => {
    smallerMd.value = breakpoints.smaller("md").value;
    open.value = !smallerMd.value ? false : open.value;
};

onMounted(() => {
    nextTick(() => {
        navbarHeight.value = navbar.value.offsetHeight;
    });
    window.addEventListener("resize", onResize);
    window.addEventListener("scroll", onScroll);
});

onBeforeUnmount(() => {
    window.removeEventListener("resize", onResize);
    window.removeEventListener("scroll", onScroll);
});
</script>
<template>
    <transition name="navbar">
        <nav v-if="showNavBar"
             class="z-10"
             :class="[
                 {'fixed inset-x-0':fixed && showNavBar},
                 {'bg-opacity-0':transparent && showHeader}
             ]">
            <!--Navbar-->
            <div ref="navbar"
                 :class="[
                     navbarClass,
                     {'bg-opacity-0':transparent && showHeader},
                     {'shadow-lg': shadow && ((transparent && lastScrollTop > navbarHeight) || !transparent && shadow) }
                 ]">
                <transition name="header">
                    <slot v-if="showHeader && $slots.header && !smallerMd"
                          name="header"/>
                </transition>
                <!--Content-->
                <div ref="content"
                     :class="[contentClass, {'container' : container}]"
                     class="grid md:grid-cols-4 items-center">
                    <!--Logo-->
                    <div class="flex justify-between items-center">
                        <div>
                            <slot name="logo">
                                <img :alt="logoAlt"
                                     :src="logoSrc"
                                     class="h-20 py-2"/>
                            </slot>
                        </div>
                        <!--Hamburguer icon-->
                        <HamburguerButton v-model="open"/>
                    </div>
                    <!-- Menu -->
                    <template v-if="!smallerMd">
                        <div class="md:col-span-3 justify-end flex h-full items-center">
                            <slot :close="closeMobileMenu"
                                  :open="open"></slot>
                        </div>
                    </template>
                </div>
            </div>
            <!-- Mobile menu-->
            <transition name="mobile-menu">
                <div v-if="open && smallerMd"
                     :class="[navbarClass]"
                     class="fixed top-0 right-20 bottom-0 left-0 drop-shadow-lg min-w-max">
                    <div class="flex justify-between items-center mx-4 md:mx-0">
                        <!-- Logo -->
                        <div>
                            <slot name="logo">
                                <img :alt="logoAlt"
                                     :src="logoSrc"
                                     class="h-20 py-2"/>
                            </slot>
                        </div>
                    </div>
                    <!-- Menu -->
                    <template v-if="open">
                        <div class="flex md:flex flex-col md:flex-row md:h-full md:w-auto justify-center md:items-center text-white gap-3 mt-8 md:mt-0">
                            <slot :close="closeMobileMenu"
                                  :open="open"></slot>
                        </div>
                    </template>
                </div>
            </transition>
        </nav>
    </transition>
    <div v-if="!transparent"
         :style="'height:' + navbarHeight + 'px'"></div>
</template>
<style scoped>
/*Header*/
.header-enter-from, .header-leave-to {
    @apply -translate-y-full h-0
}

.header-enter-to, .header-leave-from {
    @apply -translate-y-0 h-8
}

.header-enter-active, .header-leave-active {
    @apply transition-all transform ease-in-out duration-1000 overflow-hidden
}

/*Nav bar*/
.navbar-enter-from, .navbar-leave-to {
    @apply -translate-y-full
}

.navbar-enter-to, .navbar-leave-from {
    @apply -translate-y-0
}

.navbar-enter-active, .navbar-leave-active {
    @apply transition-all transform ease-in-out duration-1000
}

/*Mobile menu*/
.mobile-menu-enter-from, .mobile-menu-leave-to {
    @apply -translate-x-full
}

.mobile-menu-enter-to, .mobile-menu-leave-from {
    @apply -translate-x-0
}

.mobile-menu-enter-active, .mobile-menu-leave-active {
    @apply transition-all transform ease-in-out duration-200
}
</style>
