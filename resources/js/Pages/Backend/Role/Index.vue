<script>
import MainLayout from "@pages/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "roles_permissions", icon: "key", bc: [{label: "roles"}]
    }, () => page)
};
</script>
<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import Table from "@core/Components/Table.vue";

defineProps(["roles"]);
</script>
<template>
    <div class="flex justify-between my-0 items-center h-14 my-6">
        <span class="font-medium text-xl">{{ __("roles_list") }}</span>
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
        <Table :columns="[{name: 'name'}, {name: 'description', show: 'sm'}]"
               :data="roles"
               delete-route="admin.roles.destroy"
               divide-x
               edit-route="admin.roles.edit"
               even
               search-route="admin.roles.index"></Table>
    </div>
</template>
<style lang="scss"
       scoped></style>
