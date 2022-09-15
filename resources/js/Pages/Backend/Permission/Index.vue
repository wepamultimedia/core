<template>
    <MainLayout :bc="[{label: __('permissions')}]"
                :title="__('roles_permissions')"
                icon="key">
        <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden my-6">
            <span class="pl-4 dark:text-light font-medium text-xl">{{ __('permissions_list') }}</span>
            <span class="h-full">
                <Link :href="route('admin.permissions.create')"
                      as="button"
                      class="px-6 text-white py-2 bg-green-700 rounded-lg"
                      type="button">{{ __("create") }}
                </Link>
            </span>
        </div>
        <div class="w-full
                    overflow-hidden
                    shadow
                    rounded-lg
                    mb-20">
            <table class="table
                          table-auto
                          w-full
                          bg-white dark:bg-gray-700
                          dark:text-light
                          text-left">
                <thead class="uppercase tracking-wider text-xs font-semibold text-gray-800 dark:text-gray-300">
                    <tr>
                        <th class="bg-white dark:bg-gray-900 px-5 py-3 border-b-2 dark:border-gray-600">
                            {{ __("name") }}
                        </th>
                        <th v-show="!smallerMd"
                            class="bg-white dark:bg-gray-900 px-5 py-3 border-b-2 dark:border-gray-600">
                            {{ __("description") }}
                        </th>
                        <th class="bg-white dark:bg-gray-900 px-5 py-3 text-center border-b-2 dark:border-gray-600 w-48">
                            {{ __("actions") }}
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr>
                        <td colspan="3">
                            <div class="mx-4 my-2">
                                <input v-model="searchInput"
                                       :placeholder="__('search')"
                                       class="w-full sm:w-1/2 dark:bg-inherit border-gray-300 dark:border-gray-600 dark:text-light focus:border-skin-primary-300 focus:ring focus:ring-skin-primary-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                       type="text">
                            </div>
                        </td>
                    </tr>
                    <tr v-for="permission in permissions.data"
                        class="even:bg-gray-100 even:dark:bg-gray-600">
                        <td class="px-5 py-3  font-semibold">
                            {{ permission.name }}
                        </td>
                        <td v-show="!smallerMd"
                            class="px-5 py-3">{{ permission.description }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            <Link :href="route('admin.permissions.edit', {id:permission.id})"
                                  as="button"
                                  class="p-1 w-8 h-6">
                                <icon class="fill-dark dark:fill-light w-5 h-5"
                                      icon="pencil-alt"></icon>
                            </Link>
                            <Modal :href="route('admin.permissions.destroy', {id:permission.id})"
                                   :message="__('delete_message')"
                                   danger
                                   method="delete"
                                   :title="__('delete_permission')">
                                <template #button="{open}">
                                    <button class="p-1 w-8 h-6"
                                            @click="open">
                                        <icon class="fill-dark dark:fill-light w-5 h-5"
                                              icon="trash"></icon>
                                    </button>
                                </template>
                            </Modal>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Pagination :links="permissions.links"
                        class="m-4"/>
        </div>
    </MainLayout>
</template>
<script setup>
import MainLayout from "@layouts/Core/Backend/MainLayout.vue";
import { breakpointsTailwind, useBreakpoints } from "@vueuse/core";
import { Link } from "@inertiajs/inertia-vue3";
import { onMounted, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import _ from "lodash";
import Pagination from "@components/Pagination.vue";
import Modal from "@components/Modal.vue";

defineProps(["permissions"]);

const searchInput = ref("");
const search = _.throttle(value => {
    if (value.length > 0) {
        Inertia.get(route("admin.permissions.index"), {search: value}, {preserveState: true});
    } else {
        Inertia.get(route("admin.permissions.index"), {}, {preserveState: true});
    }
}, 100);
const breakpoints = useBreakpoints(breakpointsTailwind);
const smallerMd = breakpoints.smaller("md");

watch(searchInput, value => {
    search(value);
});

onMounted(() => {
    search(searchInput.value);
});
</script>
<style lang="scss"
       scoped></style>