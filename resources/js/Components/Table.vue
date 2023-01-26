<script setup>
import { onMounted, ref, toRefs, watch } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Modal from "@core/Components/Modal.vue";
import Pagination from "@core/Components/Pagination.vue";
import _ from "lodash";
import { Inertia } from "@inertiajs/inertia";
import { breakpointsTailwind, useBreakpoints } from "@vueuse/core";

const props = defineProps({
    data: Object,
    columns: Array,
    editRoute: String,
    viewRoute: String,
    searchRoute: String,
    deleteRoute: String,
    deleteTitle: String,
    deleteMessage: String,
    even: Boolean,
    divide: Boolean,
    divideX: Boolean,
    divideY: Boolean
});
const searchInput = ref("");
const {
          data,
          columns,
          searchRoute,
          viewRoute,
          editRoute,
          deleteRoute,
          deleteTitle,
          deleteMessage
      } = toRefs(props);

const items = ref();

const showActions = () => {
    if (viewRoute.value || editRoute.value || deleteRoute.value) {
        return true;
    }

    return false;
};
const countColumns = () => {
    if (showActions()) {
        return columns.value.length + 1;
    } else {
        return columns.value.length;
    }
};

const search = _.throttle(value => {
    if (searchRoute.value) {
        if (value.length > 0) {
            Inertia.get(route(searchRoute.value), {search: value}, {preserveState: true});
        } else {
            Inertia.get(route(searchRoute.value), {}, {preserveState: true});
        }
    }
}, 1500);

const breakpoints = useBreakpoints(breakpointsTailwind);

watch(searchInput, value => {
    if (searchRoute.value) {
        search(value);
    }
});

const showSizes = ref({
    sm: breakpoints.greaterOrEqual("sm"),
    md: breakpoints.greaterOrEqual("md"),
    lg: breakpoints.greaterOrEqual("lg"),
    xl: breakpoints.greaterOrEqual("xl"),
    "2xl": breakpoints.greaterOrEqual("2xl")
});

onMounted(() => {
    if (searchRoute.value) {
        search(searchInput.value);
    }
});
</script>
<template>
    <div class="w-full
                overflow-hidden
                shadow-lg
                rounded-lg">
        <table class="table
                      table-auto
                      w-full
                      text-left">
            <thead class="uppercase tracking-wider text-xs font-semibold">
                <tr>
                    <th v-for="col in columns"
                        v-show="col.show ? showSizes[col.show] : true"
                        class="uppercase bg-skin-inverted dark:bg-skin-inverted-dark px-5 py-3">
                        {{ col.label ? col.label : col.name ? __(col.name) : __(col) }}
                    </th>
                    <th v-if="showActions()"
                        class="bg-skin-inverted dark:bg-skin-inverted-dark px-5 py-3 text-center min-w-max">
                        {{ __("actions") }}
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <tr v-if="searchRoute">
                    <td :colspan="countColumns()">
                        <div class="mx-4 my-2">
                            <input v-model="searchInput"
                                   :placeholder="__('search')"
                                   class="w-full text-sm sm:w-1/2 dark:bg-inherit border-gray-200 dark:border-gray-600 text-skin-base dark:text-skin-base-dark focus:border-gray-200 focus:ring focus:ring-gray-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                   type="text">
                        </div>
                    </td>
                </tr>
                <template v-for="item in data.data ? data.data : data"
                          :key="item.id + '-row'">
                    <slot name="row">
                        <tr :class="{'even:bg-skin-light even:dark:bg-skin-dark': even, 'border-b border-gray-300 dark:border-gray-700 last:border-b-0' : divideX || divide}">
                            <slot :item="item"
                                  name="row-content">
                                <template v-for="col in columns" :key="item.id + '-col'">
                                    <slot :item="item"
                                          :name="col.name ? 'col-' + col.name : 'col-' + col">
                                        <td v-show="col.show ? showSizes[col.show] : true"
                                            :class="{'border-r border-gray-300 dark:border-gray-700 last:border-r-0' : divideY || divide}"
                                            class="px-5 py-3">
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
                                        </td>
                                    </slot>
                                </template>
                                <td v-if="showActions()"
                                    :class="{'border-r border-gray-300 dark:border-gray-700 last:border-r-0' : divideY || divide}"
                                    class="px-2 py-3 text-center">
                                    <slot :item="item"
                                          name="action">
                                        <Link v-if="viewRoute"
                                              :href="route(viewRoute, {id:item.id})"
                                              as="button"
                                              class="p-1 w-8 h-6">
                                            <icon class="fill-skin-base dark:fill-skin-base-dark w-5 h-5"
                                                  icon="eye"></icon>
                                        </Link>
                                        <Link v-if="editRoute"
                                              :href="route(editRoute, {id:item.id})"
                                              as="button"
                                              class="p-1 w-8 h-6">
                                            <icon class="fill-skin-base dark:fill-skin-base-dark w-5 h-5"
                                                  icon="pencil-alt"></icon>
                                        </Link>
                                        <Modal v-if="deleteRoute"
                                               :href="route(deleteRoute, {id:item.id})"
                                               :message="deleteMessage ? deleteMessage : __('delete_message')"
                                               :title="deleteTitle ? deleteTitle : __('delete_title')"
                                               danger
                                               method="delete">
                                            <template #button="{open}">
                                                <button class="p-1 w-8 h-6"
                                                        @click="open">
                                                    <icon class="fill-skin-base dark:fill-skin-base-dark w-5 h-5"
                                                          icon="trash"></icon>
                                                </button>
                                            </template>
                                        </Modal>
                                    </slot>
                                </td>
                            </slot>
                        </tr>
                    </slot>
                </template>
            </tbody>
        </table>
        <Pagination :links="data.links"
                    class="m-4"/>
    </div>
</template>
<style scoped></style>
