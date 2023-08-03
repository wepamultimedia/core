<script setup>
import _ from "lodash";
import { onMounted, ref, toRefs, watch } from "vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import Modal from "@/Vendor/Core/Components/Modal.vue";
import Input from "@/Vendor/Core/Components/Form/Input.vue";
import Pagination from "@/Vendor/Core/Components/Pagination.vue";
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

const searchInput = ref(usePage().props.ziggy?.query?.search || "");
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
    return !!(viewRoute.value || editRoute.value || deleteRoute.value);
};
const countColumns = () => {
    if (showActions()) {
        return columns.value.length + 1;
    }
    return columns.value.length;
};

const search = _.throttle(value => {
    if (searchRoute.value) {
        if (value.length > 0) {
            router.get(route(searchRoute.value), {search: value}, {preserveState: true});
        } else {
            router.get(route(searchRoute.value), {}, {preserveState: true});
        }
    }
}, 1200);

const breakpoints = useBreakpoints(breakpointsTailwind);

watch(searchInput, value => {
    if (searchRoute.value) {
        if (!value) {
            search(value);
        }
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
    if (searchRoute.value && searchInput.value !== "") {
        search(searchInput.value);
    }
});
</script>
<template>
    <table class="table
                  default
                  table-auto
                  w-full
                  text-left">
        <thead class="uppercase tracking-wider text-xs font-semibold">
            <tr>
                <th v-for="col in columns"
                    v-show="col.show ? showSizes[col.show] : true"
                    :class="[col.class, col.thClass]"
                    class="uppercase bg-skin-background px-5 py-3">
                    {{ col.label ? col.label : col.name ? __(col.name) : __(col) }}
                </th>
                <th v-if="showActions()"
                    class="bg-skin-background px-5 py-3 text-center min-w-max">
                    {{ __("actions") }}
                </th>
            </tr>
        </thead>
        <tbody class="text-sm">
            <tr v-if="searchRoute">
                <td :colspan="countColumns()">
                    <Input v-model="searchInput"
                           :placeholder="__('search')"
                           class="input w-full text-sm sm:w-1/2 !rounded-none !border-none focus:!border-0 focus:!ring-0"
                           name="search"
                           type="text"
                           @keyup.enter="search(searchInput)">
                        <template #left>
                            <div class="px-2">
                                <inline-svg class="w-5 h-5 fill-skin-base"
                                            src="/vendor/core/icons/solid/search.svg"></inline-svg>
                            </div>
                        </template>
                    </Input>
                </td>
            </tr>
            <template v-for="item in data.data ? data.data : data"
                      :key="item.id + '-row'">
                <slot name="row">
                    <tr :class="{'even:even ': even, 'div-y' : divideX || divide}">
                        <slot :item="item"
                              name="row-content">
                            <template v-for="col in columns"
                                      :key="item.id + '-col'">
                                <slot :item="item"
                                      :name="col.name ? 'col-' + col.name : 'col-' + col">
                                    <td v-show="col.show ? showSizes[col.show] : true"
                                        :class="[col.class, col.tdClass, {'div-x' : divideY || divide}]"
                                        class="px-4 py-2">
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
                                :class="{'div-y' : divideY || divide}"
                                class="px-2 py-3 text-center">
                                <slot :item="item"
                                      name="action">
                                    <slot :item="item"
                                          name="action-view">
                                        <Link v-if="viewRoute"
                                              :href="route(viewRoute, {id:item.id})"
                                              as="button"
                                              class="p-1 w-8 h-6">
                                            <icon class="fill-skin-base  w-5 h-5"
                                                  icon="eye"></icon>
                                        </Link>
                                    </slot>
                                    <slot :item="item"
                                          name="action-edit">
                                        <Link v-if="editRoute"
                                              :href="route(editRoute, {id:item.id})"
                                              as="button"
                                              class="p-1 w-8 h-6">
                                            <icon class="fill-skin-base  w-5 h-5"
                                                  icon="pencil-alt"></icon>
                                        </Link>
                                    </slot>
                                    <slot :item="item"
                                          name="action-delete">
                                        <Modal v-if="deleteRoute"
                                               :href="route(deleteRoute, {id:item.id})"
                                               :message="deleteMessage ? deleteMessage : __('delete_message')"
                                               :title="deleteTitle ? deleteTitle : __('delete_title')"
                                               danger
                                               method="delete">
                                            <template #button="{open}">
                                                <button class="p-1 w-8 h-6"
                                                        @click="open">
                                                    <icon class="fill-skin-base  w-5 h-5"
                                                          icon="trash"></icon>
                                                </button>
                                            </template>
                                        </Modal>
                                    </slot>
                                </slot>
                            </td>
                        </slot>
                    </tr>
                </slot>
            </template>
        </tbody>
    </table>
    <Pagination :links="data.meta?.links || data.links"
                class="m-4"/>
</template>
<style scoped></style>
