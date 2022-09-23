<script setup>
import MainLayout from "@layouts/Core/Backend/MainLayout.vue";
import { breakpointsTailwind, useBreakpoints } from "@vueuse/core";
import { Link } from "@inertiajs/inertia-vue3";
import { onMounted, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import _ from "lodash";
import Pagination from "@components/Pagination.vue";
import Modal from "@components/Modal.vue";
import Table from "@components/Table.vue";

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
<template>
    <MainLayout :bc="[{label: __('permissions')}]"
                :title="__('roles_permissions')"
                icon="key">
        <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden my-6">
            <span class="pl-4 dark:text-light font-medium text-xl">{{ __("permissions_list") }}</span>
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
                    bg-white dark:bg-gray-700
                    rounded-lg
                    mb-20">
            <Table :columns="['name', {name: 'description', show: 'md'}]"
                   :data="permissions"
                   delete-route="admin.permissions.destroy"
                   divide-x
                   edit-route="admin.permissions.edit"
                   search-route="admin.permissions.index"
                   even></Table>
            <Pagination :links="permissions.links"
                        class="m-4"/>
        </div>
    </MainLayout>
</template>
<style lang="scss"
       scoped></style>