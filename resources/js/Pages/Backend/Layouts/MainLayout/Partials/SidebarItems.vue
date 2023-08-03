<script setup>
import { computed, onMounted, ref, toRef } from "vue";
import { useStore } from "vuex";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    item: {
        type: Object,
        default: null
    },
    buttonClass: String,
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
const loading = ref(false);
const selectedItemId = ref(false);
const emit = defineEmits(["click"]);

const checkActive = () => {
    if (!item.value.submenu) {
        if (item.value.isActive) {
            open.value = true;
        }
    } else {
        item.value.submenu.map((i) => {
            if (i.isActive) {
                open.value = true;
            }
        });
    }
};

onMounted(() => {
    Inertia.on("start", event => {
        loading.value = true;
    });

    Inertia.on("finish", event => {
        loading.value = false;
        selectedItemId.value = null;
    });

    checkActive();
});

const open = ref(false);
const minimized = toRef(props, "minimized");
const show = computed(() => {
    return open.value && !minimized.value;
});
</script>
<template v-if="item">
    <div :class="{'bg-white bg-opacity-10 rounded-lg': show && item.hasOwnProperty('submenu')}"
         class="p-2 mb-2 transition-all duration-200 ease-in-out">
        <button class="relative flex items-center w-full min-h-max"
                :class="[buttonClass]"
                @click="open = !open">
            <div class="flex items-center w-full">
                <icon v-if="item.icon"
                      :icon="item.icon"
                      class="mr-2 w-5 h-5 min-w-max fill-skin-base "/>
                <span v-if="item.submenu && !minimized"
                      :class="{'opacity-0': minimized}"
                      class="min-w-max text-sm">
                    {{ item.label }}
                </span>
                <div v-else-if="!minimized" class="w-full">
                    <div class="flex items-center justify-between w-full">
                        <Link :class="{'active': item.isActive}"
                              :disabled="loading"
                              :href="route(item.route)"
                              :preserve-scroll="true"
                              :preserve-state="true"
                              class="text-sm text-left"
                              type="button"
                              as="button"
                              @click="store.dispatch('backend/menuActiveItem', item.id); selectedItemId = item.id; emit('click')">
                            {{ item.label }}
                        </Link>
                        <svg v-show="loading && selectedItemId === item.id"
                             aria-hidden="true"
                             class="animate-spin w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.5"
                             viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <icon v-if="item.submenu"
                  :class="[{'-rotate-0': open, '-rotate-90': !open, 'opacity-0':minimized}]"
                  class="ml-2 absolute right-0 transition-all duration-200 ease-in-out fill-skin-base "
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
                    <div class="block w-full relative" :class="[buttonClass]">
                        <span v-if="subitem.isActive"
                              class="absolute w-2 h-2 left-2 top-3 rounded-full bg-skin-base "></span>
                        <button class="flex items-center justify-between w-full">
                            <Link :class="[{'font-bold': subitem.isActive}]"
                                  :disabled="loading"
                                  :href="route(subitem.route)"
                                  :preserve-scroll="true"
                                  :preserve-state="true"
                                  as="button"
                                  class="w-full text-left py-2 pl-8"
                                  type="button"
                                  @click="store.dispatch('backend/menuActiveItem', subitem.id); selectedItemId = subitem.id; emit('click')">
                                {{ subitem.label }}
                            </Link>
                            <svg v-show="loading && selectedItemId === subitem.id"
                                 aria-hidden="true"
                                 class="animate-spin w-5 h-5"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.5"
                                 viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
        </transition>
    </div>
</template>
<style scoped></style>
