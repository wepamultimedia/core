<script>
import MainLayout from "@pages/Core/Layouts/Backend/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "roles_permissions", icon: "key", bc: [{label: "permissions"}]
    }, () => page)
};
</script>
<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import Table from "@core/Components/Table.vue";

defineProps(["permissions"]);
</script>
<template>
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden my-6">
        <span class="font-medium text-xl">{{ __("permissions_list") }}</span>
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
               even
               search-route="admin.permissions.index"></Table>
    </div>
</template>
<style lang="scss"
       scoped></style>
