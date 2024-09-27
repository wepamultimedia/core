<script setup>
import Input from "@/Vendor/Core/Components/Form/Input.vue";
import Modal from "@/Vendor/Core/Components/Modal.vue";
import Pagination from "@/Vendor/Core/Components/Pagination.vue";
import Spinner from "@/Vendor/Core/Components/Spinner.vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import {breakpointsTailwind, useBreakpoints} from "@vueuse/core";
import _ from "lodash";
import {computed, onMounted, ref, toRefs, useSlots, watch} from "vue";

const props = defineProps({
    modelValue: Object,
    data: Object,
    columns: Array, // {name: '', label: '', class: '', colSpan: ''}
    editRoute: String,
    editCallback: Function,
    viewRoute: String,
    viewCallback: Function,
    searchRoute: String,
    searchCallback: Function,
    deleteRoute: String,
    deleteCallback: Function,
    deleteTitle: String,
    deleteMessage: String,
    even: Boolean,
    divide: Boolean,
    divideX: Boolean,
    divideY: Boolean,
    emptyDataMessage: String,
    highlight: Boolean,
    paginationCallback: Function,
    loading: Boolean,
    header: {
        type: Boolean,
        default: true
    },
    extraContentProperty: String,
});

const emit = defineEmits(["update:modelValue", "update:data"]);

const searchInput = ref(usePage().props.ziggy?.query?.search || "");
const {
    columns,
    searchRoute,
    viewRoute,
    editRoute,
    deleteRoute,
    deleteTitle,
    deleteMessage
} = toRefs(props);

const items = computed(() => {
    return props.modelValue
        ? props.modelValue.data
            ? props.modelValue.data
            : props.modelValue
        : props.data.data
            ? props.data.data
            : props.data;
});

const links = computed(() => {
    return props.modelValue
        ? props.modelValue.meta.links
            ? props.modelValue.meta.links
            : props.modelValue.links
        : props.data.meta
            ? props.data.meta
            : props.data.links;


});

const countColumns = () => {
    let counter = 0;

    if (showActions()) {
        counter = counter + 1;
    }
    columns.value.forEach(c => {
        if (c.hasOwnProperty("colSpan")) {
            counter = counter + parseInt(c.colSpan);
        } else {
            counter = counter + 1;
        }
    });

    return counter;
};

const gridCols = () => {
    const counter = countColumns();
    if (counter === 1) return "grid-cols-1";
    if (counter === 2) return "grid-cols-2";
    if (counter === 3) return "grid-cols-3";
    if (counter === 4) return "grid-cols-4";
    if (counter === 5) return "grid-cols-5";
    if (counter === 6) return "grid-cols-6";
    if (counter === 7) return "grid-cols-7";
    if (counter === 8) return "grid-cols-8";
    if (counter === 9) return "grid-cols-9";
    if (counter === 10) return "grid-cols-10";
    if (counter === 11) return "grid-cols-11";
    if (counter === 12) return "grid-cols-12";
};

const colSpan = cols => {
    if (cols === 1) return "col-span-1";
    if (cols === 2) return "col-span-2";
    if (cols === 3) return "col-span-3";
    if (cols === 4) return "col-span-4";
    if (cols === 5) return "col-span-5";
    if (cols === 6) return "col-span-6";
    if (cols === 7) return "col-span-7";
    if (cols === 8) return "col-span-8";
    if (cols === 9) return "col-span-9";
    if (cols === 10) return "col-span-10";
    if (cols === 11) return "col-span-11";
    if (cols === 12) return "col-span-12";

    return "col-span-1";
};

const countActions = () => {
    let counter = 0;
    if (viewRoute.value) counter = counter + 1;
    if (editRoute.value) counter = counter + 1;
    if (deleteRoute.value) counter = counter + 1;
    if (props.viewCallback) counter = counter + 1;
    if (props.editCallback) counter = counter + 1;
    if (props.deleteCallback) counter = counter + 1;

    return counter;
};

const showActions = () => {
    return countActions() > 0;
};


const actionsClass = () => {
};

const search = _.throttle(value => {
    if (props.searchCallback) {
        props.searchCallback(value);
    } else {
        if (searchRoute.value) {
            const paramsTest = new RegExp("[\?]");
            let url = getUrl(searchRoute.value);
            if (value.length > 0) {
                if (paramsTest.test(url)) {
                    url = `${url}&search=${value}`;
                } else {
                    url = `${url}?search=${value}`;
                }
            }

            router.get(url, {}, {preserveState: true});
        }
    }
}, 1200);

const breakpoints = useBreakpoints(breakpointsTailwind);

const showSizes = ref({
    sm: breakpoints.greaterOrEqual("sm"),
    md: breakpoints.greaterOrEqual("md"),
    lg: breakpoints.greaterOrEqual("lg"),
    xl: breakpoints.greaterOrEqual("xl"),
    "2xl": breakpoints.greaterOrEqual("2xl")
});

const getUrl = (value, id = null) => {
    const routeTest = new RegExp("^http");
    if (!routeTest.test(value)) {
        return id ? route(value, {id}) : route(value);
    }

    return value;
};

watch(searchInput, value => {
    if (searchRoute.value) {
        if (!value) {
            search(value);
        }
    }
});

onMounted(() => {
    if (searchRoute.value && searchInput.value !== "") {
        search(searchInput.value);
    }
});
</script>
<template>
    <div class="relative">
        <div v-if="loading"
             class="flex flex-col justify-center items-center min-h-[200px] gap-4 absolute inset-0 bg-white/50">
            <Spinner/>
            <span class="text-lg">{{ __("loading") }}</span>
        </div>
        <div v-if="header"
             :class="[gridCols()]"
             class="grid py-3 px-5 border-b border-gray-300 dark:border-gray-500 bg-gray-100 dark:bg-gray-800 rounded-t">
            <!-- Header -->
            <div v-for="col in columns"
                 v-show="col.show ? showSizes[col.show] : true"
                 :class="[col.class, colSpan(col?.colSpan)]"
                 class="uppercase tracking-wider text-xs font-semibold">
                {{ col.label ? col.label : col.name ? __(col.name) : __(col) }}
            </div>
            <div v-if="showActions()"
                 class="uppercase tracking-wider text-xs font-semibold text-right pr-2">
                {{ __("actions") }}
            </div>
        </div>
        <!-- Search -->
        <div v-if="searchRoute"
             class="py-2 px-5">
            <Input v-model="searchInput"
                   :placeholder="__('search')"
                   class="input text-sm"
                   name="search"
                   type="text"
                   @keyup.enter="search(searchInput)">
                <template #left>
                    <div class="px-2">
                        <inline-svg class="w-4 h-4 fill-skin-base"
                                    src="/vendor/core/icons/solid/search.svg"></inline-svg>
                    </div>
                </template>
            </Input>
        </div>
        <!-- Empty data -->
        <div v-if="!items.length"
             class="col-span-full text-center">
            {{ emptyDataMessage || __("empty_data") }}
        </div>
        <!-- Body -->
        <div v-else
             :class="{'div-y' : divideY || divide}">
            <template v-for="item in items">
                <div :class="[gridCols(), {'striped': even}]"
                     class="grid items-center py-3 text-sm last:rounded-b">

                    <slot :gridCols="gridCols()"
                          :item="item"
                          name="main-row">

                        <template v-for="col in columns">
                            <slot :item="item"
                                  :name="col.name ? 'col-' + col.name : 'col-' + col">
                                <div v-show="col.show ? showSizes[col.show] : true"
                                     :class="[col.class, colSpan(col?.colSpan)]"
                                     class="first:pl-5 px-2 py-2">
                                    <slot :item="item"
                                          :name="col.name ? 'col-content-' + col.name : 'col-content-' + col">
                                        {{
                                            (item[col.name ? col.name : col] && (typeof item[col.name ? col.name
                                                : col] !== "array" && typeof item[col.name
                                                ? col.name
                                                : col] !== "object"))
                                                ? item[col.name ? col.name : col] : "..."
                                        }}
                                    </slot>
                                </div>
                            </slot>
                        </template>
                    </slot>

                    <div v-if="showActions()"
                         :class="[actionsClass()]"
                         class="py-3">
                        <div class="flex justify-end last:pr-5">
                            <slot :item="item"
                                  name="action">
                                <slot :item="item"
                                      name="extra-actions"></slot>
                                <slot :item="item"
                                      name="action-view">
                                    <Link v-if="viewRoute"
                                          :href="getUrl(viewRoute, item.id)"
                                          as="button"
                                          class="p-1 w-8 h-6">
                                        <icon class="fill-skin-base w-5 h-5"
                                              icon="eye"></icon>
                                    </Link>
                                    <button v-else-if="typeof viewCallback === 'function'"
                                            class="p-1 w-8 h-6"
                                            @click="viewCallback(item)">
                                        <icon class="fill-skin-base w-5 h-5"
                                              icon="eye"></icon>
                                    </button>
                                </slot>
                                <slot :item="item"
                                      name="action-edit">
                                    <Link v-if="editRoute"
                                          :href="getUrl(editRoute, item.id)"
                                          as="button"
                                          class="p-1 w-8 h-6">
                                        <icon class="fill-skin-base w-5 h-5"
                                              icon="pencil-alt"></icon>
                                    </Link>
                                    <button v-else-if="typeof editCallback === 'function'"
                                            class="p-1 w-8 h-6"
                                            @click="editCallback(item)">
                                        <icon class="fill-skin-base w-5 h-5"
                                              icon="pencil-alt"></icon>
                                    </button>
                                </slot>
                                <slot :item="item"
                                      name="action-delete">
                                    <Modal v-if="deleteRoute"
                                           :href="getUrl(deleteRoute, item.id)"
                                           :message="deleteMessage ? deleteMessage : __('delete_message')"
                                           :title="deleteTitle ? deleteTitle : __('delete_title')"
                                           danger
                                           method="delete">
                                        <template #button="{open}">
                                            <button class="p-1 w-8 h-6"
                                                    @click="open">
                                                <icon class="fill-skin-base w-5 h-5"
                                                      icon="trash"></icon>
                                            </button>
                                        </template>
                                    </Modal>
                                    <button v-else-if="typeof deleteCallback === 'function'"
                                            class="p-1 w-8 h-6"
                                            @click="deleteCallback(item)">
                                        <icon class="fill-skin-base w-5 h-5"
                                              icon="trash"></icon>
                                    </button>
                                </slot>
                            </slot>
                        </div>
                    </div>
                    <div v-if="extraContentProperty && item.hasOwnProperty(extraContentProperty)"
                         class="col-span-full border border-gray-300 dark:border-gray-500 my-3 m-6 ">
                        <div v-if="item.children"
                             class="flex justify-center">
                            <svg class="w-5 h-5 fill-gray-400 dark:fill-gray-400"
                                 viewBox="0 0 320 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/>
                            </svg>
                        </div>
                        <slot :item="item"
                              name="row"></slot>
                    </div>
                </div>
            </template>
        </div>
    </div>
    <Pagination v-model:links="links"
                :callback="paginationCallback"
                class="m-4"/>
</template>
<style scoped>
.striped {
    @apply even:bg-gray-200/70 even:dark:bg-gray-800/50;
}

.div-y {
    @apply divide-y divide-gray-300 dark:divide-gray-500
}

.div-x {
    @apply divide-x divide-gray-300 dark:divide-gray-600
}
</style>
