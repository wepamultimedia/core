<script setup>
import { onBeforeUnmount, onMounted, ref } from "vue";

defineProps({
    label: {
        type: String,
        default: "Open"
    },
    placement: {
        type: String,
        default: "right",
        validator: (value) => [
            "right", "left"
        ].indexOf(value) !== -1
    },
    buttonClass: String,
    onHover: Boolean,
    caret: {
        type: Boolean,
        default: true
    },
    shadow: Boolean,
    contentClass: String
});

const onEscape = (e) => {
    if (e.key === "Esc" || e.key === "Escape") {
        open.value = false;
    }
};

onMounted(() => {
    document.addEventListener("keydown", onEscape);
});

onBeforeUnmount(() => {
    document.removeEventListener("keydown", onEscape);
});

const open = ref(false);
</script>
<template>
    <span class="relative">
        <div :class="buttonClass"
             class="relative flex items-center focus:outline-none select-none text-skin-base"
             @click="open = !open"
             @mouseover="onHover ? open = true : ''">
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
                @click="open = false">
        </button>
        <!--dropdown menu-->
        <transition enter-active-class="transition-all duration-200 ease-out"
                    enter-class="opacity-0 scale-75"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition-all duration-750 ease-in"
                    leave-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-75">
            <span v-if="open"
                 :class="[{
                    'right-0': placement === 'right',
                    'left-0' : placement !== 'right',
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
            </span>
        </transition>
    </span>
</template>
<style scoped></style>