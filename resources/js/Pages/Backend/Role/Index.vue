<script setup>
import MainLayout from "@layouts/Core/Backend/MainLayout.vue";
import { breakpointsTailwind, useBreakpoints } from "@vueuse/core";
import { Link } from "@inertiajs/inertia-vue3";
import { defineProps, onMounted, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import _ from "lodash";
import Pagination from "@components/Pagination.vue";
import Modal from "@components/Modal.vue";
import Table from "@components/Table.vue";

defineProps(["roles"]);

const searchInput = ref("");
const search = _.throttle(value => {
    if (value.length > 0) {
        Inertia.get(route("admin.roles.index"), {search: value}, {preserveState: true});
    } else {
        Inertia.get(route("admin.roles.index"), {}, {preserveState: true});
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
<template>
    <MainLayout :bc="[{label: __('roles')}]"
                :title="__('roles_permissions')"
                icon="key">
        <div class="flex justify-between my-0 items-center h-14 my-6">
            <span class="pl-4 dark:text-light font-medium text-xl">{{ __("roles_list") }}</span>
            <Link :href="route('admin.roles.create')"
                  as="button"
                  class="btn btn-success text-sm"
                  type="button">{{ __("create") }}
            </Link>
        </div>
        <div class="w-full
                    overflow-hidden
                    shadow
                    bg-white dark:bg-gray-700
                    rounded-lg
                    mb-20">
            <Table :data="roles"
                   :columns="[{name: 'name'}, {name: 'description', show: 'sm'}]"
                   delete-route="admin.roles.destroy"
                   divide-x
                   edit-route="admin.roles.edit"
                   even
                   search-route="admin.roles.index">
            </Table>
            <Pagination :links="roles.links"
                        class="m-4"/>
        </div>
    </MainLayout>
</template>
<style lang="scss"
       scoped></style>