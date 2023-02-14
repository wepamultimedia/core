<script setup>
import { onBeforeUnmount, onMounted, ref } from "vue";

defineProps({
    label: {
        type: String,
        default: "Open"
    },
    left: Boolean,
    center: Boolean,
    right: Boolean,
    buttonClass: String,
    onHover: Boolean,
    caret: {
        type: Boolean,
        default: true
    },
    shadow: Boolean,
    contentClass: String
});

const onEscape = e => {
    if (e.key === "Esc" || e.key === "Escape") {
        open.value = false;
    }
};

const onClickBody = () => {
    open.value = false;
};

onMounted(() => {
    document.addEventListener("keydown", onEscape);
    document.body.addEventListener("click", onClickBody);
});

onBeforeUnmount(() => {
    document.removeEventListener("keydown", onEscape);
    document.body.removeEventListener("keydown", onClickBody);
});

const open = ref(false);
</script>
<template>
    <div class="relative text-center">
        <div :class="buttonClass"
             class="relative flex items-center justify-center"
             @mouseover="onHover ? open = true : ''"
             @click.stop="open = !open">
            <slot :open="open"
                  name="button">
                <button class="px-4 py-2 flex items-center">
                    {{ label }}
                    <inline-svg :class="{'rotate-180': open}"
                                class="transition-all duration-200 ease-out fill-dark dark:fill-light grow ml-2"
                                src="/vendor/core/icons/solid/chevron-down.svg"/>
                </button>
            </slot>
        </div>
        <!-- to close when clicked on space around it-->
        <button v-if="open"
                class="fixed inset-0 h-full w-full cursor-default focus:outline-none"
                tabindex="-1"
                @click="open = false"></button>
        <!--dropdown menu-->
        <transition name="dropdown-content">
            <div v-if="open"
                 :class="[{
                    'right-0': right,
                    'left-0' : left,
                    'left-[50%] -translate-x-[50%]' : center,
                    'shadow-lg' : shadow
                 }, contentClass]"
                 class="absolute
                        z-10
                        mt-2
                        border
                        rounded
                        text-sm
                        text-skin-base
                        bg-skin-light
                        dark:bg-skin-dark
                        dark:border-gray-600"
                 @click="open=false"
                 @mouseleave="onHover ? open = false : ''">
                <slot></slot>
            </div>
        </transition>
    </div>
</template>
<style scoped>
.dropdown-content-enter-from, .dropdown-content-leave-to {
    @apply opacity-0
}

.dropdown-content-enter-to, .dropdown-content-leave-from {
    @apply opacity-100
}

.dropdown-content-enter-active, .dropdown-content-leave-active {
    @apply transition-all ease-in-out duration-500
}
</style>
