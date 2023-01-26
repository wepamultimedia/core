<script setup>
import { computed, onMounted, ref, toRef } from "vue";
import { useStore } from "vuex";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    item: {
        type: Object, default: null
    }, buttonClass: String, dotClass: String, textClass: String, iconClass: String, onHover: Boolean, minimize: {
        type: Boolean, default: false
    }, shadow: Boolean, contentClass: String,
    onClick: {
        type: Function,
        default: () => {}
    }
});
const store = useStore();
const item = toRef(props, "item");

const checkActive = () => {
    if (!item.value.submenu) {
        if (route().current(item.value.active)) {
            open.value = true;
        }
    } else {
        item.value.submenu.map((i) => {
            if (route().current(i.active)) {
                open.value = true;
            }
        });
    }
};

onMounted(() => {
    checkActive();
});

const open = ref(false);
const minimize = toRef(props, "minimize");
const show = computed(() => {
    return open.value && !minimize.value;
});
const isActive = (item) => {
    return !!route().current(item.active);
};
</script>
<template v-if="item">
    <div :class="{'bg-white bg-opacity-10 rounded-lg': show}"
         class="p-2 mb-2 transition-all duration-200 ease-in-out">
        <button :class="[buttonClass, textClass]"
                class="relative flex items-center w-full min-h-max"
                @click="open = !open">
            <div class="flex items-center">
                <icon v-if="item.icon"
                      :class="iconClass"
                      :icon="item.icon"
                      class="mr-2 fill-skin-base w-5 h-5 min-w-max"/>
                <span v-if="item.submenu && !minimize"
                      :class="{'opacity-0': minimize}"
                      class="min-w-max text-sm">
                    {{ item.label }}
                </span>
                <Link v-else-if="!minimize"
                      :class="{'active': isActive(item)}"
                      :href="route(item.route)"
                      :preserve-scroll="true"
                      :preserve-state="true"
                      class="text-sm text-left"
                      @click="onClick()"
                      type="button">
                    {{ item.label }}
                </Link>
            </div>
            <icon v-if="item.submenu"
                  :class="[{'rotate-180': open, 'opacity-0':minimize}, iconClass]"
                  class="ml-2 absolute right-0 transition-all duration-200 ease-in-out fill-dark dark:fill-light"
                  icon="chevron-down"/>
        </button>
        <!--dropdown menu-->
        <transition enter-active-class="transition-all duration-750 ease-in-out"
                    enter-class="opacity-0 scale-0"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition-all duration-750 ease-in-out"
                    leave-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-0">
            <div v-if="item.submenu && show"
                 :class="[{'shadow-lg' : shadow}, contentClass]"
                 class="mt-4
                        relative
                        min-w-[200px] max-w-[300px]
                        text-sm">

                <template v-for="subitem in item.submenu">
                    <div class="relative">
                        <span v-if="isActive(subitem)"
                              :class="[dotClass]"
                              class="absolute w-2 h-2 bg-skin-base left-2 top-3 rounded-full"></span>
                        <Link :class="[{'font-bold': isActive(subitem)}, buttonClass, textClass]"
                              :href="route(subitem.route)"
                              :preserve-scroll="true"
                              :preserve-state="true"
                              @click="onClick()"
                              as="button"
                              class="w-full text-left py-2 pl-8"
                              type="button">{{ subitem.label }}
                        </Link>
                    </div>
                </template>
            </div>
        </transition>
    </div>
</template>
<style scoped></style>