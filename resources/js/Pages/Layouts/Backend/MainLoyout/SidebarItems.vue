<script setup>
import { computed, onMounted, ref, toRef } from "vue";
import { useStore } from "vuex";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    item: {
        type: Object,
        default: null
    },
    onHover: Boolean,
    minimized: {
        type: Boolean,
        default: false
    },
    shadow: Boolean,
    onClick: {
        type: Function,
        default: () => {
        }
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
const minimized = toRef(props, "minimized");
const show = computed(() => {
    return open.value && !minimized.value;
});
const isActive = (item) => {
    return !!route().current(item.active);
};
</script>
<template v-if="item">
    <div :class="{'bg-white bg-opacity-10 rounded-lg': show}"
         class="p-2 mb-2 transition-all duration-200 ease-in-out">
        <button class="relative flex items-center w-full min-h-max"
                @click="open = !open">
            <div class="flex items-center">
                <icon v-if="item.icon"
                      :icon="item.icon"
                      class="mr-2 w-5 h-5 min-w-max fill-skin-base dark:fill-skin-base-dark"/>
                <span v-if="item.submenu && !minimized"
                      :class="{'opacity-0': minimized}"
                      class="min-w-max text-sm">
                    {{ item.label }}
                </span>
                <Link v-else-if="!minimized"
                      :class="{'active': isActive(item)}"
                      :href="route(item.route)"
                      :preserve-scroll="true"
                      :preserve-state="true"
                      class="text-sm text-left"
                      type="button"
                      @click="onClick()">
                    {{ item.label }}
                </Link>
            </div>
            <icon v-if="item.submenu"
                  :class="[{'rotate-180': open, 'opacity-0':minimized}]"
                  class="ml-2 absolute right-0 transition-all duration-200 ease-in-out fill-skin-base dark:fill-skin-base-dark"
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
                 :class="[{'shadow-lg' : shadow}]"
                 class="mt-4
                        relative
                        min-w-[200px] max-w-[300px]
                        text-sm">
                <template v-for="subitem in item.submenu">
                    <div class="relative">
                        <span v-if="isActive(subitem)"
                              class="absolute w-2 h-2 bg-skin-base left-2 top-3 rounded-full bg-skin-base dark:bg-skin-base-dark"></span>
                        <Link :class="[{'font-bold': isActive(subitem)}]"
                              :href="route(subitem.route)"
                              :preserve-scroll="true"
                              :preserve-state="true"
                              as="button"
                              class="w-full text-left py-2 pl-8"
                              type="button"
                              @click="onClick()">{{ subitem.label }}
                        </Link>
                    </div>
                </template>
            </div>
        </transition>
    </div>
</template>
<style scoped></style>
